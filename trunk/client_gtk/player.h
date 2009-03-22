#ifndef PLAYER_H
#define PLAYER_H

#include <gtkmm.h>
#include "player.h"
#include <vector>
#include <string>

class Player
{

public:
	Player(std::string);
	virtual ~Player();

protected:
	//Signal handlers:
	

public :
	std::string name;
	std::vector<int> card_list;

};


#endif //PLAYER_H