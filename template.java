package {*pkgname*};

import java.awt.image.BufferedImage;
import java.awt.Rectangle;
import java.awt.Image;
import java.awt.*;
import java.io.*;
import java.lang.*;
import java.util.Arrays;
import java.net.URL;
import java.util.regex.Pattern;
import java.util.concurrent.TimeUnit;

import javax.imageio.ImageIO;

import org.junit.*;
import org.apache.commons.io.FileUtils;

import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.*;

import org.openqa.selenium.*;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.remote.DesiredCapabilities;
import org.openqa.selenium.remote.RemoteWebDriver;
import org.openqa.selenium.remote.Augmenter;
import org.openqa.selenium.TakesScreenshot;
import org.openqa.selenium.support.ui.Select;
import org.openqa.selenium.JavascriptExecutor;

public class {*classname*} {
  //-----
  {*d_declaration*}
  //-------------
  private String baseUrl;
  private boolean acceptNextAlert = true;
  private StringBuffer verificationErrors = new StringBuffer();

  @Before
  public void setUp() throws Exception {
    {*baseUrl*};
    //----
    {*hn_declaration*}
	//-------
  }

  @Test
  public void test{*classname*}() throws Exception {

    {*operations*}

  }

  @After
  public void tearDown() throws Exception {
    //----
	{*s_drawing*}
	//----
    
	//----
	{*quit*}
	//----
    String verificationErrorString = verificationErrors.toString();
    if (!"".equals(verificationErrorString)) {
      fail(verificationErrorString);
    }
  }

	public void GetCurrentScreenshot (WebDriver _driver, String picName) throws Exception
	{  
		// long currentY1 = (long)((JavascriptExecutor)_driver).executeScript("return  document.body.parentNode.scrollTop");
		String currentY1 = ((JavascriptExecutor)_driver).executeScript("return  document.body.parentNode.scrollTop").toString();
		int currentY = Integer.parseInt(currentY1);

		// Get the Size of the viewportWidth1
		String viewportWidth1 = ((JavascriptExecutor)_driver).executeScript("return document.body.clientWidth").toString();
		String viewportHeight1 = ((JavascriptExecutor)_driver).executeScript("return document.documentElement.clientHeight;").toString();
		int viewportWidth = Integer.parseInt(viewportWidth1);
		int viewportHeight = Integer.parseInt(viewportHeight1);

		String totalwidth1 = ((JavascriptExecutor)_driver).executeScript("return document.body.offsetWidth").toString();
		String totalHeight1 = ((JavascriptExecutor)_driver).executeScript("return  document.body.parentNode.scrollHeight").toString();
		int totalWidth = Integer.parseInt(totalwidth1);
		int totalHeight = Integer.parseInt(totalHeight1);

		// Build the Image    
		BufferedImage stitchedImage = new BufferedImage(totalWidth, totalHeight, BufferedImage.TYPE_INT_RGB);
		// Calculate the Scrolling (if needed)
		
		// Take Screenshot
		WebDriver augmentedDriver = new Augmenter().augment(_driver);
		byte[] screenshot = ((TakesScreenshot)augmentedDriver).getScreenshotAs(OutputType.BYTES);
		// Build an Image out of the Screenshot
		InputStream in = new ByteArrayInputStream(screenshot);
		BufferedImage screenshotImage = ImageIO.read(in);

		BufferedImage subImage;
		if((currentY+600)<=totalHeight){
			subImage = screenshotImage.getSubimage( 0, currentY, viewportWidth, 600 );
		}else{
			subImage = screenshotImage.getSubimage( 0, currentY, viewportWidth, viewportHeight );
		}

		FileUtils.copyFile(new File("temp.png"), new File(picName));
		ImageIO.write(subImage ,"png", new File(picName));
	}

  private boolean isElementPresent(By by) {
    try {
      driver_firefox.findElement(by);
      return true;
    } catch (NoSuchElementException e) {
      return false;
    }
  }

  private boolean isAlertPresent() {
    try {
      driver_firefox.switchTo().alert();
      return true;
    } catch (NoAlertPresentException e) {
      return false;
    }
  }

  private String closeAlertAndGetItsText() {
    try {
      Alert alert = driver_firefox.switchTo().alert();
      String alertText = alert.getText();
      if (acceptNextAlert) {
        alert.accept();
      } else {
        alert.dismiss();
      }
      return alertText;
    } finally {
      acceptNextAlert = true;
    }
  }
}
