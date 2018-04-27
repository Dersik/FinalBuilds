# -*- coding: utf-8 -*-
"""
Created on Sat Mar 17 16:07:36 2018

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
from random import *


NUM_PROCESSES = 3 #number of processes to use for calendar scraping
CALENDAR_FILE = "calendars.txt"

def gh_crawl(calendars):

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

        event_pages = []
        temp_filename = "calendar-"
        temp_filename += (calendars[0][15] + calendars[0][16])
        temp_filename += ".txt"
        f = open(temp_filename,'w')
        i = 0
        for url in calendars:
                i = i + 1
                try:
                        print("OPEN")
                        temp_pages = gh_event_urls.run(browser,url)
                        if(temp_pages):
                                event_pages.extend(temp_pages)
                        for page in temp_pages:
                                f.write(page + '\n')
        
                except NoSuchElementException:
                        continue
        f.close

        print("FINISHED CRAWLING")
        print(i)
        return

#Multiprocessing stuff
def run():
        with open(CALENDAR_FILE) as f:
                calendars = f.readlines()

        seg_length = math.ceil(len(calendars)/NUM_PROCESSES)
                
        calendar_segs = [calendars[x:x+seg_length] for x in range(0,len(calendars),seg_length)]
        pool = Pool(NUM_PROCESSES)
        results = pool.map_async(gh_crawl,calendar_segs)
        print(results.get())
        pool.close()


run()

			
