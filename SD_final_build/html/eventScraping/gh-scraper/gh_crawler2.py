

# -*- coding: utf-8 -*-
"""
Created on Tue Feb 20 12:32:13 2018

@author: mcgar
"""

import gh_crawler
from selenium.common.exceptions import NoSuchElementException

CALENDARS_FILE = "calendars_2.txt"

gh_crawler.run(CALENDARS_FILE)
