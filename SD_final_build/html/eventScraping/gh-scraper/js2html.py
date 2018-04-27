from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.chrome.options import Options
from pyvirtualdisplay import Display
import io
import time

def run(url, driver, out_file="out.txt", to_file = True, delay=5):
    """
    Small function to convert a javascript webpage to html using selenium.

    inputs
        url: the url of the page
        out_file: the name of the output text file (if to_file is true)
        to_file: whether or not to output the html to a file. If set to false the function will return a string
        driver: string representing which driver (web driver) to use. Must be set to either 'firefox' or 'chrome'
        delay: seconds to wait when loading the web page

    outputs:
        -string containing the converted html (if to_file = true)

    """

  
    driver.set_page_load_timeout(70)
    driver.implicitly_wait(70)
    
    page_loaded = False

    while(page_loaded == False):
        try:
            driver.get(url)
        except TimeoutException:
            print("TIMEOUT EXCEPTION. REFRESHING...")
            driver.refresh()
            page_loaded = False
            continue
        
        page_loaded = True
        
    time.sleep(delay)
    
    try:
        page_html = driver.page_source
    except:
        print("COULD NOT LOAD PAGE HTML (Timeout?)")
        return -1
    
    if(to_file): 
        html_file = open(out_file,"w")
        html_file.write(str(page_html.encode("utf-8")))
        html_file.close()
        return
    else:
        return str(page_html.encode("utf-8"))
