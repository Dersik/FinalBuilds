"""
MeetupScraper.py: Module to scrape Meetup.com events and insert them into a MySQL database.

"""

import meetup.api as mtup
import io
import csv
import pymysql.cursors
import datetime

API_KEY = '553a5c112c767825a58673fa65c3a'
HOST = 'localhost'
USER = 'devteam'
PASS = '=7cessPit2'
DB = 'eventFinder'

NUM_ZIPCODES = 42527 #number of zipcodes in the zipcodes table

def main():
    
    client = mtup.Client(API_KEY)

    scrape_location = get_rotation_city()

    #List of meetup event objects retrieved from "open events" on Meetup
    events = client.GetOpenEvents(country="us", city=scrape_location[0]['city'], state=scrape_location[0]['state'])

    events_to_db(pull_event_data(events))

def get_rotation_city():
    """
    Gets the current city in the city rotation so that every time the scraper runs a different city location is scraped. The current city information is stored and retrieved from the database.

    Returns
        A dict containing the current location with two keys, 'state' and 'city'.
    """

    connection = pymysql.connect(host=HOST, user=USER, password=PASS, db=DB, charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
    cursor = connection.cursor()

    query = "SELECT `col1` FROM `configuration` WHERE `id`=2"
    cursor.execute(query) 
    city_id = int(cursor.fetchone()['col1'])#id of the current city to find events in

    query = "SELECT `city`, `state` FROM zipCodes WHERE `id`=" + str(city_id)
    cursor.execute(query)
    location = cursor.fetchall()

    #if above max in table then reset
    if(city_id + 1 >= NUM_ZIPCODES):
        city_id = 1

    query = "UPDATE `configuration` SET `col1`=" + str(city_id+1) + " WHERE `id`=2"
    cursor.execute(query)

    connection.commit()

    return location
    
def pull_event_data(in_events):
    """

     Pulls useful event data from list of Meetup event objects and returns them as a multidimensional list

     Args:
         in_events: list of retrieved Meetup API events as MeetupObjects.

     Returns
         Mulidimensional list of event information in the format:
         [name, time, city, state, latitude, longitude, description, source(meetup)]

    """

    rows = []

    for event in in_events.__dict__['results']:

        event_row = []
        event_row.extend([event['name'], str(event['time'])[:-3]])
        
        if 'venue' in event and 'city' in event['venue']:
            if 'state' in event['venue']:
                event_row.extend([event['venue']['name'], event['venue']['city'], event['venue']['state'], str(event['venue']['lat']), str(event['venue']['lon'])])
            else:
                event_row.extend([event['venue']['name'], event['venue']['city'], "", str(event['venue']['lat']), str(event['venue']['lon'])])
        else:
            event_row.extend(["","","","",""])

        if 'description' in event:
            event_row.append(event['description'])
        else:
            event_row.append("")

        event_row.append("meetup")
        rows.append(event_row)

    return rows

def events_to_db(events_list):
    """
    Takes list of event data and inserts it into an SQL database.
    """
    
    connection = pymysql.connect(host=HOST, user=USER, password=PASS, db=DB, charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
    cursor = connection.cursor()
	
    for event in events_list:
        query = "INSERT INTO events (name, date, locationName, cityName, stateName, latitude, longitude, description, source) VALUES ( \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")" % tuple(event)
        query.encode('unicode_escape')

        #Gotta find ways to work around these errors. They occur when certain unusable characters are in the descriptions that SQL can't store for one reason or another.
        try:
            cursor.execute(query)
        except pymysql.err.ProgrammingError:
            continue
        except pymysql.err.InternalError:
            continue

    connection.commit()
    
main()
