#!/usr/bin/python
# coding=UTF-8

#Includes
from PySFML import sf
import time
import math

#Creation of the main window
window = sf.RenderWindow(sf.VideoMode(800, 600), "Tarotaf")

#Initial parameters
init_x=40          #X-Position of the deck
init_y=400        #Y-Position of the deck
bounce=30      #Height for the "bounce" effect of the card
uncover=25     #Uncover the left of the cards
#Lists that contain cards files and cards sprites
img = []
card = []
#Initial filling of the lists
for i in range(20):
	img.append(sf.Image())
	img[i].LoadFromFile("img/"+str(i)+".png")
	card.append(sf.Sprite(img[i]))
	card[i].SetX(card[i].GetPosition()[0]+uncover*i+init_x)
	card[i].SetY(init_y)
	card[i].SetScale(0.5,0.5)	
#Dimension of a card
width = card[0].GetSize()[0]
height = card[0].GetSize()[1]
#Selected card (Dafault = last one)
ind=len(card)-1

start=True  #Boolean, True as long as the deck hasn't been visited
click=False #Boolean to determine if a Drag'n'Drop is initiated

# On dmarre la boucle de jeu
running = True

while running:
	event = sf.Event()
	while window.GetEvent(event):
		if event.Type == sf.Event.Closed:
			running = False
			
		#On a mouse click, determine...
		if event.Type == sf.Event.MouseButtonPressed:
			#...if the cursor is on a card of the deck...
			if(event.MouseButton.Y>init_y and event.MouseButton.Y<init_y+height and event.MouseButton.X>init_x and event.MouseButton.X<init_x+uncover*(len(card)-1)+width):
				click=True
				init=True
			#...or on the top of a bouncing card
			if(event.MouseButton.Y<init_y and event.MouseButton.Y>init_y-30 and  event.MouseButton.X>init_x and event.MouseButton.X<init_x+uncover*(len(card)-1)+card[ind].GetSize()[0]):
				click=True
				init=True
				
		#On a mouse drop	
		if event.Type == sf.Event.MouseButtonReleased:
			if click:
				card[ind].SetPosition(init_x+uncover*ind,init_y-30)							
				click=False
				init=False	
		
		#On a mouse gesture
		if event.Type == sf.Event.MouseMoved:
			
			#Mouse is over the deck and the player isn't clicking
			if(event.MouseMove.Y>init_y and event.MouseMove.Y<init_y+height and event.MouseMove.X>init_x and event.MouseMove.X<init_x+uncover*(len(card)-1)+width) and click==False:
				#Not the last card
				if int(math.floor((event.MouseMove.X-init_x)/uncover))<=(len(card)-1):
					#Zoom out the previous card and unbounce it...
					if int(math.floor((event.MouseMove.X-init_x)/uncover)) != ind and start==False:
						card[ind].SetScale(0.5,0.5)
						card[ind].SetY(card[ind].GetPosition()[1]+30)
					ind = int(math.floor((event.MouseMove.X-init_x)/uncover))
					start=False
				else:
					if int(math.floor((event.MouseMove.X-init_x)/uncover)) > ind and start==False:
						card[ind].SetScale(0.5,0.5)
						card[ind].SetY(card[ind].GetPosition()[1]+30)						
					ind = len(card)-1
					start=False
				#Zoom on the card and make it bounce !...
				card[ind].SetScale(0.6,0.6)
				#...if not already
				if card[ind].GetPosition()[1]==init_y:
					card[ind].SetY(card[ind].GetPosition()[1]-30)
			
			#If the card is dragged
			if click:
				#It's necessary to store the position of the cursor, here its distance with the card position
				if init:
					dx = init_x+uncover*ind - event.MouseMove.X
					dy = (init_y+height-card[ind].GetSize()[1]) - 30 - event.MouseMove.Y
					init=False		
				#New position of the card
				card[ind].SetPosition(dx+event.MouseMove.X,dy+event.MouseMove.Y)	


	#Screen painting
	window.Clear(sf.Color(0,128,0))
	
	#Cards drawing
	for i in range(20):
		window.Draw(card[i])
	
	#Display the main window	
	window.Display()
