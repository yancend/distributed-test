/**
 * Date: 2013-12-01
 * 为了远程部署准备的文件创建时的回调
 */

var fs = require("fs");
var path = require("path");
var util = require("util");
var Url = require('url');
var _exists = fs.existsSync || pth.existsSync;

var log4js = require('log4js');
var StringUtils = require("underscore.string");
var EventEmitter = require('events').EventEmitter;

module.exports = function(config, filePath, stat, syncStatusEmitter) {

	var paths = filePath.split("/");
	var pkgName = paths[paths.length - 2] + "." + paths[paths.length - 1];
	var className = paths[paths.length - 1];
	var fileName = filePath + "/" + className + ".java";
	var configPath = filePath.substr(0, filePath.lastIndexOf("/") + 1) + "config.txt";
	var config = fs.readFileSync(configPath, "utf-8").split(",");
	var content = fs.readFileSync(fileName, "utf-8");
	var template = fs.readFileSync("template.java", "utf-8");

	var d_declaration ="";
	for(var i in config){
		d_declaration += 'private WebDriver driver_' + config[i] + ';' + '\n';
	}

	var hn_declaration = 'String nodeURL = "http://localhost:4444/wd/hub";' + '\n';
	for(var i in config){
		hn_declaration += '//' + config[i] + '\n';
		if(config[i].indexOf("ie") == 0)
			hn_declaration += 'DesiredCapabilities capability_' + config[i] + ' = DesiredCapabilities.internetExplorer();' + '\n';
		else
			hn_declaration += 'DesiredCapabilities capability_' + config[i] + ' = DesiredCapabilities.' + config[i] + '();' + '\n';
		hn_declaration += 'driver_' + config[i] + ' = new RemoteWebDriver(new URL(nodeURL),capability_' + config[i] + ');' + '\n';
		hn_declaration += 'driver_' + config[i] + '.manage().timeouts().implicitlyWait(10, TimeUnit.SECONDS);' + '\n';
	}

	var s_drawing = '';
	for(var i in config){
		s_drawing += '//' + config[i] + '\n';
		s_drawing += 'driver_' + config[i] + '.manage().window().maximize();'+ '\n';
		s_drawing += 'Thread.sleep(100);'+ '\n';
		s_drawing += 'GetCurrentScreenshot(driver_' + config[i] + ', "pics/' + pkgName.replace(".", "/") + '/' + config[i] + '.png");'+ '\n';
	}

	var quit = '';
	for(var i in config){
		quit += 'driver_' + config[i] + '.quit();'+ '\n';
	}

	var temp = content.substring(content.indexOf('baseUrl = '));
	var baseUrl = temp.substring(0, temp.indexOf(';'));

	var temp = content.substring(content.indexOf('@Test'), content.indexOf('@After'));
	temp = temp.substring(temp.indexOf('{') + 1, temp.indexOf('}'));
	var operations = '';
	for(var i in config){
		operations += temp.replace(/driver/g, 'driver_' + config[i]);
	}

	template = template.replace('{*pkgname*}', pkgName).replace("{*classname*}", className)
		.replace('{*d_declaration*}', d_declaration).replace('{*baseUrl*}', baseUrl)
		.replace('{*hn_declaration*}', hn_declaration).replace('{*operations*}', operations)
		.replace('{*s_drawing*}', s_drawing).replace('{*quit*}', quit).replace("{*classname*}", className);

	fs.open(fileName, "w", 0644, function(e, fd){
		if(e) throw e;
		fs.write(fd, template ,0, 'utf8', function(e){
			if(e) throw e;
			fs.closeSync(fd);
		})
	});
	
    var sys = require('sys')
	var exec = require('child_process').exec;
    var paths = filePath.split("/");
	var cmd =  "ant -Dtestname " + paths[paths.length - 2] + "." + paths[paths.length - 1] + "." + paths[paths.length - 1] 
		+ " -Dsrcpath src/" + paths[paths.length - 2] + "/" + paths[paths.length - 1];
    exec(cmd, function (error, stdout, stderr) {
        sys.print('stdout: ' + stdout + "\r");
		if (stderr !== "") {
            sys.print('stderr: ' + stderr + "\r");
		}
        if (error !== null) {
            console.log('exec error: ' + error + "\r");
        }
    });
	syncStatusEmitter.emit("synced");
};
