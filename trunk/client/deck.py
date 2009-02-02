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
		self.init_x=20          #X-Position of the deck
		self.init_y=window.GetHeight()-140       #Y-Position of the deck
		self.bounce=30      #Height for the "bounce" effect of the card
		self.uncover=25     #Uncover the left of the cards
		#Lists that contain cards files and cards sprites
		self.img = []
		self.card = []
		#Load cards image files
		for i in range(51):
			self.img.append(sf.Image())
			self.img[i].LoadFromFile("img/"+str(i)+".png")
		#Set the card in the hand
		self.set_cards()
		#Dimension of a card
		self.width = self.card[0].GetSize()[0]
		self.height = self.card[0].GetSize()[1]
		#Selected card (Dafault = last one)
		self.ind=len(self.card)-1
		

		self.start=True  #Boolean, True as long as the deck hasn't been visited
		self.click=False #Boolean to determine if a Drag'n'Drop is initiated
		self.init=False
			
	def on_click(self):
		#...if the cursor is on a card of the deck...
		if(self.event.MouseButton.Y>self.init_y and self.event.MouseButton.Y<self.init_y+self.height and self.event.MouseButton.X>self.init_x and self.event.MouseButton.X<self.init_x+self.uncover*(len(self.card)-1)+self.width):
			self.click=True
			self.init=True
			
		#...or on the top of a bouncing card
		if(self.event.MouseButton.Y<self.init_y and self.event.MouseButton.Y>self.init_y-30 and  self.event.MouseButton.X>self.init_x and self.event.MouseButton.X<self.init_x+self.uncover*(len(self.card)-1)+self.card[self.ind].GetSize()[0]):
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
			self.card[self.ind].SetPosition(self.init_x+self.uncover*self.ind,self.init_y-30)							
			self.click=False
			self.init=False	

	def on_move(self):
		#Mouse is over the deck and the player isn't clicking
		if(self.event.MouseMove.Y>self.init_y and self.event.MouseMove.Y<self.init_y+self.height and self.event.MouseMove.X>self.init_x and self.event.MouseMove.X<self.init_x+self.uncover*(len(self.card)-1)+self.width) and self.click==False:
			#Not the last card
			if int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover))<=(len(self.card)-1):
				#Zoom out the previous card and unbounce it...
				if int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover)) != self.ind and self.start==False:
					self.card[self.ind].SetScale(0.5,0.5)
					self.card[self.ind].SetY(self.card[self.ind].GetPosition()[1]+30)
				self.ind = int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover))
				self.start=False
			else:
				if int(math.floor((self.event.MouseMove.X-self.init_x)/self.uncover)) > self.ind and self.start==False:
					self.card[self.ind].SetScale(0.5,0.5)
					self.card[self.ind].SetY(self.card[self.ind].GetPosition()[1]+30)						
				self.ind = len(self.card)-1
				self.start=False
			#Zoom on the card and make it bounce !...
			self.card[self.ind].SetScale(0.6,0.6)
			#...if not already
			if self.card[self.ind].GetPosition()[1]==self.init_y:
				self.card[self.ind].SetY(self.card[self.ind].GetPosition()[1]-30)

		#If the card is dragged
		if self.click:
			#It's necessary to store the position of the cursor, here its distance with the card position
			if self.init:				
				self.dx = self.init_x+self.uncover*self.ind - self.event.MouseMove.X
				self.dy = (self.init_y+self.height-self.card[self.ind].GetSize()[1]) - 30 - self.event.MouseMove.Y
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

	def display(self):	
		#Cards drawing
		for i in range(20):
			self.window.Draw(self.card[i])
	#set_card : store the sprites of the card that compose the deck
	def set_cards(self):
		for card in self.player.hand:
			self.card.append(sf.Sprite(self.img[card]))
			self.card[card].SetX(self.card[card].GetPosition()[0]+self.uncover*card+self.init_x)
			self.card[card].SetY(self.init_y)
			self.card[card].SetScale(0.5,0.5)
