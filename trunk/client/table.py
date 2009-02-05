#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf
import time
import math
import random
from configobj import ConfigObj


class Table:
	
	def __init__(self,window,event,room):
		self.window = window
		self.room=room
		self.event=event
		self.init_x=5               #x margin
		self.init_y=5               #y margin
		self.width=self.window.GetWidth()          
		self.height=self.window.GetHeight()*0.85
		self.box_height=self.window.GetHeight()*0.13
		self.box_width=self.box_height*1.62                  #Perfect box :)  
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
		#"Themable" vars
		self.box_color=[]
		self.box_border_thickness=0
		self.box_border_color=[]
		self.load_theme()
				
		
		
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
		self.boxes.append(sf.Shape.Rectangle(x,y,x+self.box_width,y+self.box_height,sf.Color(self.box_color[0],self.box_color[1],self.box_color[2],self.box_color[3]),self.box_border_thickness,sf.Color(self.box_border_color[0],self.box_border_color[1],self.box_border_color[2],self.box_border_color[3])))
		#Name
		self.names.append(sf.String(self.room.players[id].name))
		self.names[id].SetPosition(x+1,y+1)
		self.names[id].SetSize(20)
		#self.names[id].Rotate(3)		
		
		self.nb_players = self.nb_players + 1
	
	#add_card : create the card to be displayed   ====> To be modified
	def add_card(self,player):
		#To be changed to adapt the size of the screen
		img = sf.Image()
		img.LoadFromFile("img/"+str(self.room.players[player].hand[0])+".png")
		self.cards[player] = sf.Sprite(img)
		self.cards[player].SetScale(0.3,0.3)
		
		#Player 0
		if player ==0:
			#Position
			x=self.init_x+int(self.width/2)-int(self.cards[player].GetSize()[0]/2)
			y=self.height-self.init_y-self.box_height-self.box_height-random.randint(30,40)			
			
		#Player 1 
		elif player ==1:
			#Position
			x=self.init_x+self.box_width+random.randint(30,40)
			y=self.init_y+int(self.height/2)-int(self.cards[player].GetSize()[1]/2)			
			
		#Player 2
		elif player ==2:
			#Position
			x=self.init_x+int(self.width/2)-int(self.cards[player].GetSize()[0]/2)
			y=self.init_y+self.box_height+random.randint(30,40)	
			
		#Player 3 
		elif player ==3:
			#Position
			x=self.width-self.init_x-self.box_width-self.cards[player].GetSize()[0]-random.randint(30,40)
			y=self.init_y+int(self.height/2)-int(self.cards[player].GetSize()[1]/2)	
		
		
		#self.cards[player].SetPosition(2*self.init_x+self.box_width,self.init_y+int(self.height/2)-int(self.cards[player].GetSize()[1]/2))
		self.cards[player].SetPosition(x,y)		
		#self.cards[player].Rotate(random.randint(-12,12))
		
	#on_drop : print the drop zone and tell if it's in or out
	def on_drop(self):
		if (self.mouse_x > self.init_x+int(self.width/2) - int(self.drop_width/2) and self.mouse_x < self.init_x+int(self.width/2) + int(self.drop_width/2) and self.mouse_y < self.height-self.init_y and self.mouse_y > self.height-self.init_y-self.drop_height):
			return True
		else:
			return False
	#load_theme : load the theme from the conf file and apply it to the table		
	def load_theme(self):
		config = ConfigObj("./themes/default/theme.conf") #Load the config file
		background = sf.Image()
		background.LoadFromFile("./themes/default/background.png")		
		self.background = sf.Sprite(background)
		self.background.Resize(self.window.GetWidth(),self.window.GetHeight())		
		#Color of the players' boxes
		for color in config['box_color']:
			self.box_color.append(int(color))
		if len(self.box_color)==3:
			self.box_color.append(255) #If transparency is not specified
		#Color of the players' boxes border		
		for color in config['box_border_color']:
			self.box_border_color.append(int(color))
		if len(self.box_border_color)==3:
			self.box_border_color.append(255) #If transparency is not specified
		#Thickness of the border
		self.box_border_thickness=int(config['box_border_thickness'])
		
		#Size of the table based on the size of the window
		self.width=self.window.GetWidth()
		self.height=int(0.85*self.window.GetHeight())
			
			
			
			