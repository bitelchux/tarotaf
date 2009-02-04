#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf
import time

class Menu:
	
	def __init__(self,window,event):
		self.window=window
		self.event=event
		self.visible=False
		self.background_color=sf.Color(0,0,0,240)
		self.width=250
		self.width_hidden=0
		self.height=self.window.GetHeight()
		self.background = sf.Shape.Rectangle(self.window.GetWidth()-self.width_hidden, 0, self.window.GetWidth(), self.height, self.background_color)
		
	
	def display(self):		
		self.window.Draw(self.background)
			
	def show(self):
		if self.visible==False:
			self.visible=True
			self.background = sf.Shape.Rectangle(self.window.GetWidth()-self.width, 0, self.window.GetWidth(), self.height, self.background_color)			
		else:
			self.visible=False
			self.background = sf.Shape.Rectangle(self.window.GetWidth()-self.width_hidden, 0, self.window.GetWidth(), self.height, self.background_color)
		
	def on_move(self):
		if self.visible==False:
			if (self.event.MouseMove.X>self.window.GetWidth()-self.width_hidden and self.event.MouseMove.X<self.window.GetWidth()):
				self.show()
		