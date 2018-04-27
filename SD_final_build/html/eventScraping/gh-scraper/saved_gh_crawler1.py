

# -*- coding: utf-8 -*-
"""
Created on Tue Feb 20 12:32:13 2018

@author: mcgar
"""
from multiprocessing import Pool
import gh_event_urls
import gh_event_scraper
from selenium.common.exceptions import NoSuchElementException
import math

from selenium import webdriver
import time
from pyvirtualdisplay import Display
from selenium.common.exceptions import WebDriverException
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException
display = Display(visible=0, size=(800, 800))  
display.start()

try:
    chrome_options = Options()
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--disable-setuid-sandbox")
    browser = webdriver.Chrome(chrome_options=chrome_options)
except:
    print("PROBLEM MAKING SESSION")
    display.sendstop()


browser.set_page_load_timeout(70)
browser.implicitly_wait(70)



with open ("calendar-.w.txt") as f:
    events = f.readlines()
    
for event in events:
    if '?/#' in event:
        a = 1
    else:
        event = event.replace('\n','')
        gh_event_scraper.run(event,browser)


