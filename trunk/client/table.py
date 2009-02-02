#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf
import time
import math
import random


class Table:
	
	def __init__(self,window,event,room):
		self.window = window
		self.room=room
		self.event=event
		self.init_x=5               #x margin
		self.init_y=5               #y margin
		self.width=1000          
		self.height=525          
		self.box_width=100     
		self.box_height=40
		self.drop_width=300
		self.drop_height=200
		self.nb_players=0        #Nb of actual players on the table			
		#Boxes definition
		self.boxes = []    #Background of the boxes
		self.names = []   #Names of the players
		self.cards=[]       #Cards displayed on the table
		for i in range(5):			
			self.cards.append(sf.Sprite(sf.Image()))
		#Mouse position
		self.mouse_x=0
		self.mouse_y=0
		
		img = sf.Image()
		img.LoadFromFile("img/back.png")
		self.back = sf.Sprite(img)
		
		
		
	#display_* : display the items (sprites, strings...) on the window	
	def display_card(self):
		for card in self.cards:
			self.window.Draw(card)
		
	def display_players(self):
		for box in self.boxes:
			self.window.Draw(box)
		for name in self.names:
			self.window.Draw(name)
	
	#add_box : build the players's boxes	
	def add_box(self):		
		id = self.nb_players    #Player's id
		
		#Player 0
		if self.nb_players ==0:
			#Position
			x=self.init_x+int(self.width/2)-int(self.box_width/2)
			y=self.height-self.init_y-self.box_height			
			
		#Player 1 
		elif self.nb_players ==1:
			#Position
			x=self.init_x
			y=self.init_y+int(self.height/2)-int(self.box_height/2)			
			
		#Player 2
		elif self.nb_players ==2:
			#Position
			x=self.init_x+int(self.width/2)-int(self.box_width/2)
			y=self.init_y
			
		#Player 3 
		elif self.nb_players ==3:
			#Position
			x=self.width-self.init_x-self.box_width
			y=self.init_y+int(self.height/2)-int(self.box_height/2)
			
		#Background
		self.boxes.append(sf.Shape.Rectangle(x,y,x+self.box_width,y+self.box_height,sf.Color(0,0,0,45)))
		#Name
		self.names.append(sf.String(self.room.players[id].name))
		self.names[id].SetPosition(x+1,y+1)
		self.names[id].SetSize(20)
		self.names[id].Rotate(9)		
		
		self.nb_players = self.nb_players + 1
	
	#add_card : create the card to be displayed   ====> To be modified
	def add_card(self,player):
		
		img = sf.Image()
		img.LoadFromFile("img/"+str(self.room.players[player].hand[0])+".png")
		self.cards[player] = sf.Sprite(img)
		self.cards[player].SetScale(0.3,0.3)
		
		#Player 0
		if player ==0:
			#Position
			x=self.init_x+int(self.width/2)-int(self.box_width/2)
			y=self.height-2*self.init_y-self.box_height-self.box_height-self.cards[player].GetSize()[1]			
			
		#Player 1 
		elif player ==1:
			#Position
			x=2*self.init_x+self.box_width+random.randint(25,50)
			y=self.init_y+int(self.height/2)-int(self.box_height/2)			
			
		#Player 2
		elif player ==2:
			#Position
			x=self.init_x+int(self.width/2)-int(self.box_width/2)
			y=2*self.init_y+self.box_height+random.randint(25,50)	
			
		#Player 3 
		elif player ==3:
			#Position
			x=self.width-2*self.init_x-self.box_width-self.cards[player].GetSize()[0]-random.randint(25,50)
			y=self.init_y+int(self.height/2)-int(self.box_height/2)	
		
		
		#self.cards[player].SetPosition(2*self.init_x+self.box_width,self.init_y+int(self.height/2)-int(self.cards[player].GetSize()[1]/2))
		self.cards[player].SetPosition(x,y)		
		#self.cards[player].Rotate(random.randint(-12,12))
		
	#on_drop : print the drop zone and tell if it's in or out
	def on_drop(self):
		if (self.mouse_x > self.init_x+int(self.width/2) - int(self.drop_width/2) and self.mouse_x < self.init_x+int(self.width/2) + int(self.drop_width/2) and self.mouse_y < self.height-self.init_y and self.mouse_y > self.height-self.init_y-self.drop_height):
			return True
		else:
			return False