#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf
import time
import math

class Deck:

	def __init__(self,window,event,player,table):
		#Initial parameters
		self.window = window
		self.event = event
		self.player=player   #Player 0
		self.table=table      #Table associated to the room
		self.init_x=5          #X-Position of the deck
		self.init_y=int(self.window.GetHeight()*0.85)       #Y-Position of the deck
		self.bounce=20      #Height for the "bounce" effect of the card
		self.uncover=25     #Uncover the left of the cards
		self.over_card=False #True if over a card
		#Lists that contain cards files and cards sprites
		self.img = []
		self.card = []
		#Load cards image files
		for i in range(51):
			self.img.append(sf.Image())
			self.img[i].LoadFromFile("img/"+str(i)+".png")
		#Dimension of a card
		self.width = 0
		self.height = 0
		#Set the card in the hand
		self.set_cards()		
		#Selected card (Default = last one)
		self.ind=len(self.card)-1
		
		

		self.start=True  #Boolean, True as long as the deck hasn't been visited
		self.click=False #Boolean to determine if a Drag'n'Drop is initiated
		self.init=False   #True on the very begining of the drag, to store the mouse location
			
	def on_click(self):
		#...if the cursor is on a card of the deck...
		if(self.event.MouseButton.Y>self.init_y and self.event.MouseButton.Y<self.init_y+self.height and self.event.MouseButton.X>self.init_x and self.event.MouseButton.X<self.init_x+self.uncover*(len(self.card)-1)+self.width*(self.window.GetHeight()*0.15)/self.height):
			self.click=True
			self.init=True
			
		#...or on the top of a bouncing card
		if(self.event.MouseButton.Y<self.init_y and self.event.MouseButton.Y>self.init_y-self.bounce and  self.event.MouseButton.X>self.init_x and self.event.MouseButton.X<self.init_x+self.uncover*(len(self.card)-1)+self.card[self.ind].GetSize()[0]):
			self.click=True
			self.init=True
			
	
	def on_drop(self):		
		if self.click:
			if self.table.on_drop():
				print "IN !"
			else:
				print "out ... :("
			#Apply the transparency if not on the drop zone
			color = self.card[self.ind].GetColor()			
			color.a=255
			self.card[self.ind].SetColor(color)
			self.card[self.ind].SetPosition(self.init_x+self.uncover*self.ind,self.init_y-self.bounce)	
			#self.card.pop(self.ind)
			self.click=False
			self.init=False	

	def on_move(self):
		#Mouse is over the deck and the player isn't clicking
		if(self.event.MouseMove.Y>self.init_y and self.event.MouseMove.Y<self.init_y+self.height and self.event.MouseMove.X>self.init_x and self.event.MouseMove.X<self.init_x+self.uncover*(len(self.card)-1)+self.width*(self.window.GetHeight()*0.15)/self.height) and self.click==False:
			self.over_card = True #If the mouse is over a card
			#Not the last card
			if int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover))<=(len(self.card)-1):
				#Zoom out the previous card and unbounce it...
				if int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover)) != self.ind and self.start==False:
					scale = (self.window.GetHeight()*0.15)/self.height	
					self.card[self.ind].SetScale(scale,scale)
					self.card[self.ind].SetY(self.init_y)
				self.ind = int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover))
				self.start=False
			else:
				if int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover)) > self.ind and self.start==False:
					scale = (self.window.GetHeight()*0.15)/self.height
					self.card[self.ind].SetScale(scale,scale)
					self.card[self.ind].SetY(self.init_y)
				self.ind = len(self.card)-1
				self.start=False
			#Zoom on the card and make it bounce !...
			scale = (self.window.GetHeight()*0.15)/self.height+0.065
			self.card[self.ind].SetScale(scale,scale)
			#...if not already
			if self.card[self.ind].GetPosition()[1]==self.init_y:
				self.card[self.ind].SetY(self.card[self.ind].GetPosition()[1]-self.bounce)
		else:
			self.over_card=False #If the mouse isn't over a card

		#If the card is dragged
		if self.click:
			#It's necessary to store the position of the cursor, here its distance with the card position
			if self.init:				
				self.dx = self.init_x+self.uncover*self.ind - self.event.MouseMove.X
				self.dy = (self.init_y+self.height*self.scale-self.card[self.ind].GetSize()[1]) - self.bounce - self.event.MouseMove.Y
				self.init=False		
			#New position of the card			
			self.card[self.ind].SetPosition(self.dx+self.event.MouseMove.X,self.dy+self.event.MouseMove.Y)
			#Store the position of the mouse for the drop
			self.mouse_x=self.event.MouseMove.X
			self.mouse_Y=self.event.MouseMove.Y
			#Apply the transparency if not on the drop zone
			color = self.card[self.ind].GetColor()
			if self.table.on_drop():
				color.a=255
				self.card[self.ind].SetColor(color)
			else:				
				color.a=50
				self.card[self.ind].SetColor(color)
		
		#If the card is quit from a side where there is no other card then make the last card go back to normal
		if not self.over_card and not self.click:
			scale = (self.window.GetHeight()*0.15)/self.height	
			self.card[self.ind].SetScale(scale,scale)
			self.card[self.ind].SetY(self.init_y)
			

	def display(self):	
		#Cards drawing
		for card in self.card:
			self.window.Draw(card)
	#set_card : store the sprites of the card that compose the deck
	def set_cards(self):
		self.init_y=int(self.window.GetHeight()*0.85) #Redefine the Y initial position		
		del self.card[:] #Empty the card list
		
		for card in self.player.hand:
			self.card.append(sf.Sprite(self.img[card]))			
			self.width = self.card[card].GetSize()[0]
			self.height = self.card[card].GetSize()[1]			
			self.scale = (self.window.GetHeight()*0.15)/self.height
			self.card[card].SetScale(self.scale,self.scale)
			#rebu = (int(self.window.GetWidth()*0.70)-len(self.player.hand)*self.width*self.scale)/len(self.player.hand)
			#self.card[card].SetX(self.width*self.scale*card+(card)*rebu+self.init_x)
			self.card[card].SetX(self.uncover*card+self.init_x)
			self.card[card].SetY(self.init_y)