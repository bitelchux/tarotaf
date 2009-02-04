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

#Config file
mode=sf.VideoMode(1024,768)

#Creation of the main window
window = sf.RenderWindow(mode, "Tarotaf",sf.Style.Close)
window.SetFramerateLimit(25)

#Init
event = sf.Event()

#TRY ZONE
rooms = []
rooms.append(Room(window,event,'Mother'))
table = Table(window,event,rooms[0])
rooms[0].add_player(Player(0,'Elie',table))
rooms[0].add_player(Player(1,'JM',table))
rooms[0].add_player(Player(2,'Toto',table))
rooms[0].add_player(Player(3,'Fifou',table))
for i in range(23):
	rooms[0].players[0].add_card(i)
rooms[0].players[1].add_card(42)
rooms[0].players[2].add_card(19)
rooms[0].players[3].add_card(28)
table.add_box();table.add_box();table.add_box();table.add_box();
deck = Deck(window,event,rooms[0].players[0],table)
menu=Menu(window,event)
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
				deck.on_click()
			if event.MouseButton.Button==1:
				menu.show()
			
		#On a mouse drop	
		if event.Type == sf.Event.MouseButtonReleased:			
			deck.on_drop()
			
		#On a mouse gesture
		if event.Type == sf.Event.MouseMoved:
			table.mouse_x=event.MouseMove.X
			table.mouse_y=event.MouseMove.Y
			deck.on_move()
			menu.on_move()
			
		if event.Type == sf.Event.KeyPressed:			
			if event.Key.Code==sf.Key.Escape:
				running=False
				window.Create(mode, "Tarotaf",sf.Style.Close)	
				window.Show(False)				
			if event.Key.Code==sf.Key.F11 and  fullscreen:				 
				window.Create(mode, "Tarotaf",sf.Style.Close)	
				table.load_theme()	
				deck.set_cards()				
			if event.Key.Code==sf.Key.F11 and not fullscreen:
				window.Create(mode, "Tarotaf",sf.Style.Fullscreen)
				deck.set_cards()
				table.load_theme()					
			if event.Key.Code==sf.Key.F11:
				fullscreen= not fullscreen
				
	#Screen painting
	window.Clear(sf.Color(24,100,0))
	
	#Display everything	
	window.Draw(table.background)
	table.display_card()
	table.display_players()
	menu.display()
	deck.display()
	#Display the main window	
	window.Display()
	