tarotaf: interface.o table.o main.o chat.o player.o room.o
	g++ -o tarotaf interface.o table.o main.o chat.o player.o room.o `pkg-config gtkmm-2.4 --cflags --libs` 

interface.o: interface.cpp 
	g++ -o interface.o -c interface.cpp `pkg-config gtkmm-2.4 --cflags --libs`

table.o: table.cpp
	g++ -o table.o -c table.cpp `pkg-config gtkmm-2.4 --cflags --libs`  
	
chat.o: chat.cpp
	g++ -o chat.o -c chat.cpp `pkg-config gtkmm-2.4 --cflags --libs`
	
player.o: player.cpp
	g++ -o player.o -c player.cpp `pkg-config gtkmm-2.4 --cflags --libs`
	
room.o: room.cpp
	g++ -o room.o -c room.cpp `pkg-config gtkmm-2.4 --cflags --libs`

main.o: main.cpp main.h interface.h table.h chat.h player.h room.h def.h
	g++ -o main.o -c main.cpp `pkg-config gtkmm-2.4 --cflags --libs`

clean:
	rm -rf *.o
 
