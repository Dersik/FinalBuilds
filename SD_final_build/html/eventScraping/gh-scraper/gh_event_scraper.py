import js2html
from scrapy.selector import Selector
import pymysql.cursors
from datetime import datetime

HOST = 'localhost'
USER = 'devteam'
PASS = '=7cessPit2'
DB = 'eventFinder'

def run(page_url, browser):
    """
    Scrapes a Gatehouse event page and inserts the event data into the database.

    Inputs:
        page_url - URL to scrape
        browser - browser to use for the selenium driver. Defaults to chrome.
    
    """
    print("starting scraper")
    html_page = js2html.run(url=page_url, to_file=False, driver=browser, delay=5)

    #if js2html didn't work (from a timeout exception or session not created) then it returns -1
    if(html_page == -1):
        return -1

    event = scrape_event_page(html_page)
    gh_event_to_db(event)

    return 1

def gh_event_to_db(event_line):
    """
    Inserts a formatted row of event data (as a list) into the SQL events table.

    Inputs:
        event_line - row of event data formatted in the style of the events table.
    
    """

    #if the html on the page wasn't correctly loaded then event_line will be set to -1
    if(event_line == -1):
        return    
    
    connection = pymysql.connect(host=HOST, user=USER, password=PASS, db=DB, charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
    cursor = connection.cursor()
    
    query = "INSERT INTO events (name, date, locationName, cityName, stateName, latitude, longitude, description, source) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")" % tuple(event_line)
    query.encode('unicode_escape')

    #Gotta find ways to work around these errors. They occur when certain unusable characters are in the descriptions that SQL can't store for one reason or another.
    try:
        cursor.execute(query)
    except pymysql.err.ProgrammingError:
        connection.close()
        print("MYSQL PROGRAMMING ERROR")
        return
    except pymysql.err.InternalError:
        connection.close()
        print("MYSQL INTERNAL ERROR")
        return

    connection.commit()

def scrape_event_page(page):
    """
    Scrapes event data and returns it as a list formatted in the style of the SQL table

    Inputs:
        page - string containing raw html data of a Gatehouse event page.

    Outputs:
        A formatted list of event data in the format: [name, (unix) time, address/location, city, state, latitude, longitude, description, source]
    
    """

    event_data = []

    title = Selector(text=page).xpath("//h1[@class='event-title']/text()").extract()
    event_data.extend(title)

    date = Selector(text=page).xpath("//p[@class='event-date evie-primary-color']/text()").extract()
    event_data.extend(date)

    location = Selector(text=page).xpath("//div[@class='widget address']/p/text()").extract()
    event_data.extend(location)

    description = Selector(text=page).xpath("//div[@class='event-content']/text()").extract()
    event_data.extend(description)

    #If the html isn't properly loaded then nothing will be scraped. Return -1 in this case. Its <2 because of some weird error where it was length 1 but thats it.
    if(len(event_data) < 2):
        print("HTML LOAD ERROR")
        return -1

    # final_event_row = []
    # final_event_row.append(event_data[0]) #append name
    # final_event_row.append(parse_date(event_data[1])) #time
    # final_event_row.append(event_data[2][1:]) #address/location name
    # final_event_row.extend(parse_latlon(event_data[3])) #city, state, latitude, longitude
    # temp_desc = "".join(event_data[4:])
    # final_event_row.append(temp_desc) #description
    # final_event_row.append("gatehouse") #source
	
	
    final_event_row = []
    if (event_data[0]):
         final_event_row.append(event_data[0]) #append name
    else:
          final_event_row.append("Not Available")
    if (event_data[1]):
         final_event_row.append(parse_date(event_data[1])) #time
    else:
         final_event_row.append("Not Available")
    if(event_data[2][1:])	:
         final_event_row.append(event_data[2][1:]) #address/location name
    else:
        final_event_row.append("Not Available")
    if(event_data[3]):
        
        latlon_templine = parse_latlon(event_data[3])
        
        if(latlon_templine == -1):
            print("LOCATION NOT FOUND")
            return -1
        
        final_event_row.extend(latlon_templine) #city, state, latitude, longitude
    else:
        final_event_row.append("Not Available")
	
    temp_desc = "".join(event_data[4:])
	

    if (temp_desc):
        final_event_row.append(temp_desc) #description
    else:
        final_event_row.append("Not Available")
    final_event_row.append("gatehouse") #source
    
    
    return final_event_row

def parse_date(date_line):
    """
    Parses the date and time from the format on the gatehouse event pages and converts it to unix time.

    Inputs:
         date_line - date/time string in the format on gatehouse event pages

    Outputs:
         The event date/time converted to a unix timestamp
    
    """

    formats = ["%a, %b %d, %Y %I:%M %p ", "%a, %b %d, %Y"] #different timestamp formats to try on the date_line
    date_line = date_line.split('-',1)[0]

    try:
        timestamp = datetime.strptime(date_line, formats[0]).timestamp() #date as unix timetamp
    except ValueError:
        try:
            timestamp = datetime.strptime(date_line, formats[1]).timestamp()
        except ValueError:
            return -1

    return int(timestamp)

def parse_latlon(city_line):
    """
    Parses latitude and longitude using the zipcode table in the database.

    Inputs:
         city_line - The line from a Gatehouse media event that contains the city, state and zipcode of the event.

    Outputs:
         A list in the format: [city, state, latitude, longitude]
    
    """

    #Split city, state and zipcode into separate list elements
    try:
        lines = city_line.split(',')
        lines.extend(lines[1][1:].split(' '))
        lines.pop(1)
    except IndexError:
        return -1

    connection = pymysql.connect(host=HOST, user=USER, password=PASS, db=DB, charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
    cursor = connection.cursor()

    latlon = None #instantiate latlon so that it can be used in the following conditionals without an error

    #if zipcode is provided
    if(len(lines)>2):
        
        try:
            query = "SELECT `latitude`,`longitude` FROM zipCodes WHERE `zipCode` = " + lines[2]
            cursor.execute(query)
            latlon = cursor.fetchall()[0]
        except pymysql.err.ProgrammingError:
            connection.close()
            print("MYSQL PROGRAMMING ERROR")
            return -1
        except:
            connection.close()
            return -1
    
    if((not latlon) or (not lines[2]) or (not latlon['longitude'] or not latlon['latitude'])):
        
        try:
            query = "SELECT `latitude`,`longitude` FROM zipCodes WHERE `city` = '" + lines[0] + "' AND `state` = '" + lines[1] + "'"
            cursor.execute(query)
        except pymysql.err.ProgrammingError:
            connection.close()
            print("MYSQL PROGRAMMING ERROR")
            return -1
        except:
            connection.close()
            return -1
        
        try:
            latlon = cursor.fetchall()[0]
        except:
            connection.close()
            return -1
    
    latlon['longitude'] = float(latlon['longitude'])
    latlon['latitude'] = float(latlon['latitude'])

    connection.commit()

    return [lines[0][1:], lines[1], str(latlon['latitude']), str(latlon['longitude'])]
