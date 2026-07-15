const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  
  page.on('console', msg => console.log('PAGE LOG:', msg.type(), msg.text()));
  page.on('pageerror', error => console.log('PAGE ERROR:', error.message));

  await page.goto('http://127.0.0.1:8000/login');
  await page.type('#email', 'ruzena@gmail.com');
  await page.type('#password', 'swatantra');
  await Promise.all([
    page.click('button[type="submit"]'),
    page.waitForNavigation()
  ]);
  
  await page.goto('http://127.0.0.1:8000/profile');
  
  // wait 2 seconds using promise
  await new Promise(r => setTimeout(r, 2000));
  
  const outer = await page.$eval('main#main-content', el => el.outerHTML);
  console.log('MAIN HTML:', outer.substring(0, 500));
  
  await browser.close();
})();
