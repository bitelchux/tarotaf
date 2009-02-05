#!/usr/bin/python
# coding=UTF-8

# TODO
# -Themes 
# -Players boxes and avatars
# -Menu
# -Textbox
#
#
#
#
#

#Includes
from PySFML import sf
import time
import math
from deck import Deck
from table import Table
from player import Player
from menu import Menu
from room import Room
from switcher import Switcher

#Beurk
def add_room(window,event,name,players):				
	global rooms,tables,decks,menus,switcher,thumbs,new_room_screenshot
	rooms.append(Room(window,event,name))
	id = len(rooms)-1
	tables.append(Table(window,event,rooms[id]))
	i=0
	for player in players:
		rooms[id].add_player(Player(i,player,tables[id]))
		tables[id].add_box();
		i=i+1
	for j in range(18):
		rooms[id].players[0].add_card(j)
	for k in range(i-1):
		rooms[id].players[k+1].add_card(42)
	decks.append(Deck(window,event,rooms[id].players[0],tables[id]))
	thumbs.append(sf.Image())	
	menus.append(Menu(window,event))
	switcher.nb_rooms=switcher.nb_rooms+1
	switcher.thumbs2=thumbs
	new_room_screenshot=True

#Config file
mode=sf.VideoMode(1024,768)

#Creation of the main window
window = sf.RenderWindow(mode, "Tarotaf",sf.Style.Close)
window.SetFramerateLimit(25)

#Init
event = sf.Event()



#TRY ZONE
rooms = []
tables=[]
decks=[]
menus=[]
switchers=[]
thumbs=[]
switcher=(Switcher(window,event,thumbs))		
room=0
add_room(window,event,'Mother',('Elie','Jacques','Pierre','François'))
#~ add_room(window,event,'Daughter',('Nicolas','Vlad','Patrick'))
#~ add_room(window,event,'Ministers',('Michele','Christine','Marc'))
#~ add_room(window,event,'Prez',('Marie-Annick','Patou'))
#~ add_room(window,event,'Co-Prez',('Jeanine','Fred'))
#~ add_room(window,event,'Parents',('Nathalie','Loic'))
#~ add_room(window,event,'PE',('Manu','Ed','David','Lemur'))
#~ add_room(window,event,'Bests',('Jo','Yarusnas'))

fullscreen=False


#Starting the main loop
running = True
while running:		
	while window.GetEvent(event):
		
		if event.Type == sf.Event.Closed:
			running = False
			
		#On a mouse click, determine...
		if event.Type == sf.Event.MouseButtonPressed:
			if event.MouseButton.Button ==0:
				decks[room].on_click()
				if switcher.over_thumb and switcher.visible:
					room = switcher.thumb_in
			if event.MouseButton.Button==1:			
				thumbs[room]=window.Capture()							
				#menus[room].show()
				if room==len(rooms)-1:
					room=0
				else:
					room=room+1
				switcher.generate()
			if event.MouseButton.Button==2:
				add_room(window,event,'PE',('Manu','Ed','David','Lemur'))
				room = len(rooms)-1
		
		#On a mouse drop	
		if event.Type == sf.Event.MouseButtonReleased:			
			decks[room].on_drop()
			
		#On a mouse gesture
		if event.Type == sf.Event.MouseMoved:
			tables[room].mouse_x=event.MouseMove.X
			tables[room].mouse_y=event.MouseMove.Y
			decks[room].on_move()
			menus[room].on_move()
			switcher.on_move()
			
		if event.Type == sf.Event.KeyPressed:			
			if event.Key.Code==sf.Key.Escape:
				running=False
				window.Create(mode, "Tarotaf",sf.Style.Close)	
				window.Show(False)				
			if event.Key.Code==sf.Key.F11 and  fullscreen:				 
				window.Create(mode, "Tarotaf",sf.Style.Close)	
				tables[room].load_theme()	
				decks[room].set_cards()				
			if event.Key.Code==sf.Key.F11 and not fullscreen:
				window.Create(mode, "Tarotaf",sf.Style.Fullscreen)
				decks[room].set_cards()
				tables[room].load_theme()					
			if event.Key.Code==sf.Key.F11:
				fullscreen= not fullscreen				
			
	#Screen painting
	window.Clear(sf.Color(24,100,0))
	#Display everything	
	window.Draw(tables[room].background)
	tables[room].display_card()
	tables[room].display_players()
	menus[room].display()
	decks[room].display()
	#If a room has been just added, take a picture and display it
	if new_room_screenshot:
		thumbs[room]=window.Capture()	
		switcher.generate()
		new_room_screenshot=False
		
	if len(rooms)>1:
		switcher.display()
	else:
		switcher.visible=False
	#Display the main window	
	window.Display()

	
