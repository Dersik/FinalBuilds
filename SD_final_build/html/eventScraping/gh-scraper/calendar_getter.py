from selenium import webdriver

browser = webdriver.Chrome()
url = "http://www.gatehousemedia.com/our-markets/#1455242747862-cd1dc18f-e377"
browser.get(url) #navigate to the page

links = []
calendarLinks = []

elems = browser.find_elements_by_xpath("//a[@href]")
for elem in elems:
    if not "gatehouse" in elem.get_attribute("href").lower():
        #print (elem.get_attribute("href"))
        links.append(elem.get_attribute("href"))


for link in links:
    if link.endswith("/"):
        print (link + "calendar")
        calendarLinks.append(link + "calendar")
    else:
        browser.get(link)
        browser.implicitly_wait(15)    
        elems = browser.find_elements_by_xpath("//a[@href]")
        for elem in elems:
            if "calendar" in elem.get_attribute("href").lower():
                calendarLinks.append(elem.get_attribute("href"))
                print (elem.get_attribute("href"))
                break;
            else:
                continue;

for cL in calendarLinks:
   print (cL)

