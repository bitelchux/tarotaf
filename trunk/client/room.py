#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf

class Room:
	
	def __init__(self,window,event,name):
		self.window = window
		self.event = event
		self.name = name
		self.players = []
		
		
	def add_player(self,player):
		self.players.append(player) #Add a new player to the room