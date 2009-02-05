#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf
import math


class Switcher:
	
	def __init__(self,window,event,thumbs):
		self.window=window
		self.event=event
		self.thumbs2=thumbs		
		self.init_x=self.window.GetWidth()*0.65
		self.init_y=self.window.GetHeight()*0.86
		self.width=300
		self.height=100		
		self.nb_rooms=0
		self.thumbs=[]
		self.visible=False
		self.over_thumb=False
		self.thumb_in=0
		self.scale=0
		self.generate()
	
	#generate : build the switcher
	def generate(self):		
		del self.thumbs[:] #Empty the list of switcher's thumbnails
		self.background = sf.Shape.Rectangle(self.init_x,self.init_y,self.init_x+self.width,self.init_y+self.height,sf.Color(75,71,41,100),1,sf.Color(255,255,255)) #Print the background
		#Add the thumb for each room
		for i in range(self.nb_rooms):				
			self.thumbs.append(sf.Sprite(self.thumbs2[i]))
			#4 thumbs max per line
			if self.nb_rooms<=4:
				#Find the good scale factor
				if self.thumbs[i].GetSize()[0]!=0:
					self.scale=min((self.width-5*(self.nb_rooms+1))/(self.nb_rooms*self.thumbs[i].GetSize()[0]),(self.height-10)/self.thumbs[i].GetSize()[1])
					self.thumbs[i].SetScale(self.scale,self.scale)
				self.thumbs[i].SetY(self.init_y+(self.height-self.thumbs[i].GetSize()[1])/2)
				self.thumbs[i].SetX(self.init_x+self.thumbs[i].GetSize()[0]*i+(self.width-self.nb_rooms*self.thumbs[i].GetSize()[0])/(self.nb_rooms+1)*(i+1))
			else:
				#Find the good scale factor
				if self.thumbs[i].GetSize()[0]!=0:
					self.scale=min((self.width-5*(4+1))/(4*self.thumbs[i].GetSize()[0]),(self.height-15)/(2*self.thumbs[i].GetSize()[1]))
					self.thumbs[i].SetScale(self.scale,self.scale)
				#Draw the thumbs on 2 lignes if more than 4 thumbs
				if i>3:
					self.thumbs[i].SetY(self.init_y+2*(self.height-2*self.thumbs[i].GetSize()[1])/3+self.thumbs[i].GetSize()[1])
					self.thumbs[i].SetX(self.init_x+self.thumbs[i].GetSize()[0]*(i-4)+(self.width-4*self.thumbs[i].GetSize()[0])/5*((i-4)+1))	
				else:
					self.thumbs[i].SetY(self.init_y+(self.height-2*self.thumbs[i].GetSize()[1])/3)
					self.thumbs[i].SetX(self.init_x+self.thumbs[i].GetSize()[0]*i+(self.width-4*self.thumbs[i].GetSize()[0])/5*(i+1))
				
	#display : draw the switcher on the window
	def display(self):					
		self.window.Draw(self.background)
		for thumb in self.thumbs:
			self.window.Draw(thumb)
		self.visible=True
		
	def on_move(self):
		if(self.event.MouseMove.X>self.init_x and self.event.MouseMove.X<self.init_x+self.width and self.event.MouseMove.Y>self.init_y and self.event.MouseMove.Y<self.init_y+self.height and self.visible):
			i=0
			j=-1
			for thumb in self.thumbs:
				if (self.event.MouseMove.X>thumb.GetPosition()[0] and self.event.MouseMove.X<thumb.GetPosition()[0]+thumb.GetSize()[0] and self.event.MouseMove.Y>thumb.GetPosition()[1] and self.event.MouseMove.Y<thumb.GetPosition()[1]+thumb.GetSize()[1]):
					position = thumb.GetPosition()
					width=thumb.GetSize()[0]
					height=thumb.GetSize()[1]				
					thumb.SetScale(self.scale*1.2,self.scale*1.2)
					thumb.SetX(position[0]-(thumb.GetSize()[0]-width)/2)
					thumb.SetY(position[1]-(thumb.GetSize()[1]-height)/2)
					j=i
					if j!=self.thumb_in:
						position = self.thumbs[self.thumb_in].GetPosition()
						width=self.thumbs[self.thumb_in].GetSize()[0]
						height=self.thumbs[self.thumb_in].GetSize()[1]				
						self.thumbs[self.thumb_in].SetScale(self.scale,self.scale)
						self.thumbs[self.thumb_in].SetX(position[0]-(self.thumbs[self.thumb_in].GetSize()[0]-width)/2)
						self.thumbs[self.thumb_in].SetY(position[1]-(self.thumbs[self.thumb_in].GetSize()[1]-height)/2)
					self.over_thumb=True
					self.thumb_in=i
				i+=1	
			if j==-1:
				self.over_thumb=False			
		else:
			self.over_thumb=False
		if not self.over_thumb and self.visible:
			position = self.thumbs[self.thumb_in].GetPosition()
			width=self.thumbs[self.thumb_in].GetSize()[0]
			height=self.thumbs[self.thumb_in].GetSize()[1]				
			self.thumbs[self.thumb_in].SetScale(self.scale,self.scale)
			self.thumbs[self.thumb_in].SetX(position[0]-(self.thumbs[self.thumb_in].GetSize()[0]-width)/2)
			self.thumbs[self.thumb_in].SetY(position[1]-(self.thumbs[self.thumb_in].GetSize()[1]-height)/2)
			


