# -*- coding: utf-8 -*-
"""
Created on Thu Feb  1 10:25:14 2018

@author: mcgar
"""

from selenium import webdriver
import time
from pyvirtualdisplay import Display
from selenium.common.exceptions import WebDriverException
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException

NUM_PAGES = 10 #number of event pages to cycle through

def run(browser,url):
    time.sleep(1)
    #array of links
    links = []

    # display = Display(visible=0, size=(800, 800))  
    # display.start()

    # try:
        # chrome_options = Options()
        # chrome_options.add_argument("--no-sandbox")
        # chrome_options.add_argument("--disable-setuid-sandbox")
        # browser = webdriver.Chrome(chrome_options=chrome_options)
    # except:
        # print("PROBLEM MAKING SESSION")
        # display.sendstop()
        # return run(url)
    
    browser.set_page_load_timeout(70)
    browser.implicitly_wait(70)

    #non-event link
    bad = url
    bad += "?/#"
    
    page_loaded = False

    while(not page_loaded):
        try:
            browser.get(url)
        except TimeoutException:
            print("TIMEOUT EXCEPTION. REFRESHING...")
            browser.refresh()
            page_loaded = False
            continue
        
        page_loaded = True
    
    #go for 10 pages
    for x in range (0,NUM_PAGES):
        time.sleep(1)
        print(browser.current_url)
        time.sleep(5)
        #get each event link
        div = browser.find_element_by_class_name('main')
        divs = div.find_elements_by_class_name('thumb')
        if (not divs):
            print("empty page")
        else:
            for div in divs:
                elems = div.find_elements_by_css_selector('a')
                for elem in elems:
                    links.append(elem.get_attribute('href'))
                
        print('end of page')
        time.sleep(.5)
        #click next page(day)
        button = browser.find_element_by_class_name("next")
        browser.execute_script("arguments[0].click();", button)
    
    #remove non-event links
    links = list(filter(lambda a: a != bad, links))
    return links
    
 
