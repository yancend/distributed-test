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
