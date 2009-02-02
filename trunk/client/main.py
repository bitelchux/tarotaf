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

#Creation of the main window
window = sf.RenderWindow(sf.VideoMode(1000,700), "Tarotaf",sf.Style.Close)
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
for i in range(25):
	rooms[0].players[0].add_card(i)
rooms[0].players[1].add_card(42)
rooms[0].players[2].add_card(19)
rooms[0].players[3].add_card(28)
table.add_box();table.add_box();table.add_box();table.add_box();
deck = Deck(window,event,rooms[0].players[0],table)
menu=Menu(window,event)



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
			
	#Screen painting
	window.Clear(sf.Color(0,128,0))
	
	#Display everything
	window.Draw(table.back)
	table.display_card()
	table.display_players()
	menu.display()
	deck.display()
	#Display the main window	
	window.Display()
	