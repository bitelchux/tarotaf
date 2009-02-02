#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf


class Player:
	
	def __init__(self,id,name,table):
		self.name=name
		self.table=table
		self.id=id
		self.hand=[] #Cards in the player's hand or on the table if not player 0		
	
	# add_card : add a card in the player's hand or on the table
	def add_card(self,card):
		self.hand.append(card)
		self.table.add_card(self.id)
		