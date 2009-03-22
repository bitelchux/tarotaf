#include "player.h"
#include <iostream>
#include <string>

Player::Player(std::string name_)
{
	name = name_;
	card_list.push_back(6);
	card_list.push_back(2);
	card_list.push_back(45);
	card_list.push_back(17);
	card_list.push_back(23);
	card_list.push_back(38);
	card_list.push_back(10);
	card_list.push_back(31);
}

Player::~Player()
{
}


