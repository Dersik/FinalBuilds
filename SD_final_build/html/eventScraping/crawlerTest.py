#command to run the crawler
#scrapy runspider crawlerTest.py

import scrapy
import logging

class eventFinder(scrapy.Spider):
  #misc crawler information and settings
  name = "eventFinder"
  logging.getLogger('scrapy').setLevel(logging.WARNING)
  
  #seed url(s)
  start_urls = ['http://www.buyersedge.com']
  
  f = open('crawlerOutput.txt', 'w')
  f.close

  def parse(self, response):
    f = open('crawlerOutput.txt', 'a')

    #get content from current page
    titleSelector = 'head title ::text'
    print(str(response.css(titleSelector).extract_first()))
    yield{'title': response.css(titleSelector).extract_first(),}
    
    #select link for next page
    nextSelector = 'a ::attr(href)'
    links = response.css(nextSelector).extract()
    
    for link in links:
      next = response.urljoin(link)
      f.write(next + '\n')
      
      if next:
        yield scrapy.Request(next)
