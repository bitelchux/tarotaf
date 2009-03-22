#include "table.h"
#include <iostream>
#include <algorithm>
#include <sstream>
#include <string>
#include <stdlib.h>

/*//////////////////////
//
//  TODO : -Func draw card
//               -INvaliate rect only deck
//               - struct card -> jumped
///////////////////////*/
Table::Table(std::vector<card_file* >* card_file_list_)
{
	set_size_request(600,600);
	
	//Initialize the pointers
	card_pointed = new card;
	card_file_list = card_file_list_;
	
	//Add the differents kind of events
	add_events(Gdk::POINTER_MOTION_MASK );
	add_events(Gdk::BUTTON_PRESS_MASK);
	add_events(Gdk::BUTTON_RELEASE_MASK);
	
	//Load all the image files and store them
	background.source = Gdk::Pixbuf::create_from_file("background.png");
	
		
	card_drag=false;
	drag_save=false;
	width=0;
	height=0;
}

Table::~Table()
{
}

/*
// on_expose_event : draw the background and the cards and if needed rescale them
// 
*/
bool Table::on_expose_event(GdkEventExpose* event)
{	
	back=Gdk::Pixmap::create(get_window(),get_width(),get_height(),get_window()->get_depth()); 
	back_drag=Gdk::Pixmap::create(get_window(),get_width(),get_height(),get_window()->get_depth()); 

	//Rescale only if the size has changed
	if(get_width()!=width || get_height()!=height)
	{	
		std::cout<<"Resize"<<std::endl;			
		width=get_width();
		height=get_height();		
		
		//Scale the background
		double ratio = std::min(1.*get_width()/background.source->get_width(),1.*get_height()/background.source->get_height());	
		background.scaled = background.source->scale_simple(ratio*background.source->get_width()+1,ratio*background.source->get_height()+1,Gdk::INTERP_BILINEAR);
				
		for (card_it=card_list.begin();card_it!=card_list.end();card_it++)
		{
			//Scale the cards
			ratio = std::min(0.15*get_width()/(*card_it)->card_f->source->get_width(),0.15*get_height()/(*card_it)->card_f->source->get_height());
			(*card_it)->card_f->scaled =(*card_it)->card_f->source->scale_simple(ratio*(*card_it)->card_f->source->get_width(),ratio*(*card_it)->card_f->source->get_height(),Gdk::INTERP_BILINEAR);
			
		}

			
		
	}
	
	std::cout<<"Redraw"<<std::endl;

	//Draw the table
	draw_table();
	
	return true;
}

bool Table::on_motion_notify_event(GdkEventMotion* event)
{
	//std::cout<<"Move"<<std::endl;
	if(!card_drag)
	{
		int c=on_deck();
		//If true
		if(c!=-1)
		{
			card_quit=true;			
			
			//If not the current pointed card, update
			if(card_list[c] != card_pointed)
			{
				card_pointed->pointed=false;
				card_list[c]->pointed=true;
				card_pointed = card_list[c];
				draw_table();
			}						
		}
		//Or not
		else
		{
			if(card_quit)
			{
				for (card_it=card_list.begin();card_it!=card_list.end();card_it++)
				{			
					(*card_it)->pointed=false;
				}
				//Force the table to be drawn again to put back the card in the deck
				card_quit=false;
				card_pointed = new card;
				draw_table();			
			}
			
		}
	}
	else
	{
		draw_table();
	}
	return true;	
}

void Table::draw_table()
{
	//If drag and drag inital save not done
	if(!drag_save && card_drag)
	{
		//Draw a proper version		
		draw_background(); 
		draw_deck();
		//Save it in a pixmap
		back_drag->draw_drawable(get_style()->get_black_gc(),back,
							0,0,0,0,-1,-1);
		drag_save=true;
		get_window()->draw_drawable(get_style()->get_black_gc(),back_drag,
								0,0,0,0,-1,-1); 
	}
	if(!card_drag)
	{
		//Draw the differents parts of the table
		draw_background();
		draw_deck();	

		//Display the entire pixmap
		get_window()->draw_drawable(get_style()->get_black_gc(),back,
								0,0,0,0,-1,-1); 
	}
	//Low processor - Inspired by Belooted
	else
	{
		int x;int y;
		get_pointer(x,y);
		int ax,ay,aw,ah,bw,bh,bx,by;
		
		if(abs(x-card_dragged->drag_xb)>card_dragged->card_f->scaled->get_width() || abs(y-card_dragged->drag_yb)>card_dragged->card_f->scaled->get_height())
		{
			std::cout<<"X Grand"<<std::endl;
			ax=card_dragged->drag_xb-card_dragged->drag_x;
			ay=card_dragged->drag_yb-card_dragged->drag_y;
			aw=card_dragged->card_f->scaled->get_width();
			ah=card_dragged->card_f->scaled->get_height();
			bx=by=bw=bh=0;
		}
		else
		{
			if(x>card_dragged->drag_xb)
			{
				if(y>card_dragged->drag_yb)
				{
					ax=card_dragged->drag_xb-card_dragged->drag_x;
					ay = card_dragged->drag_yb-card_dragged->drag_y;
					bx=ax;by=ay;
					ah= card_dragged->card_f->scaled->get_height();
					aw=x-card_dragged->drag_xb;	
					bh=y-card_dragged->drag_yb;
					bw=card_dragged->card_f->scaled->get_width();	
					
				}
				else
				{
					ax=card_dragged->drag_xb-card_dragged->drag_x;
					ay=card_dragged->drag_yb-card_dragged->drag_y;
					
					bx=ax;
					by=y+(card_dragged->card_f->scaled->get_height()-card_dragged->drag_y);
					
					ah= card_dragged->card_f->scaled->get_height();					
					aw=x-card_dragged->drag_xb;
					
					bh=card_dragged->drag_yb-y;
					bw=card_dragged->card_f->scaled->get_width();				
					
				}							
			}
			else
			{
				if(y>card_dragged->drag_yb)
				{
					ax=card_dragged->drag_xb-card_dragged->drag_x;
					ay=card_dragged->drag_yb-card_dragged->drag_y;
					
					bx=x-card_dragged->drag_x+card_dragged->card_f->scaled->get_width();
					by=ay;
					
					ah=y-card_dragged->drag_yb;
					aw=card_dragged->card_f->scaled->get_width();
					
					bh=card_dragged->card_f->scaled->get_height();
					bw=card_dragged->drag_xb-card_dragged->drag_x;	
					
				}
				else
				{
					ax=card_dragged->drag_xb-card_dragged->drag_x;
					ay=y+(card_dragged->card_f->scaled->get_height()-card_dragged->drag_y);
					
					bx=x+(card_dragged->card_f->scaled->get_width()-card_dragged->drag_x);
					by=card_dragged->drag_yb-card_dragged->drag_y;
					
					ah=card_dragged->drag_yb-y;
					aw=card_dragged->card_f->scaled->get_width();
					
					bh=card_dragged->card_f->scaled->get_height();
					bw=card_dragged->drag_xb-x;				
					
				}			
			}
			
			
		}		
		
		//Load the saved inital table
		get_window()->draw_drawable(get_style()->get_black_gc(),back_drag,
								ax,ay,
								ax,ay,
								aw,ah);
		
		get_window()->draw_drawable(get_style()->get_black_gc(),back_drag,
								bx,by,
								bx,by,
								bw,bh);
		
		card_dragged->drag_xb=x;
		card_dragged->drag_yb=y;
		
		
		
		//Draw the dragged card
		get_window()->draw_pixbuf(get_style()->get_black_gc(),card_dragged->card_f->scaled,
						0, 0,x-card_dragged->drag_x,y-card_dragged->drag_y,-1,-1,Gdk::RGB_DITHER_NONE, 0, 0);	
		
		
	}	

}


void Table::draw_background()
{	
	//If no moving card or drag inital save not done
	if(!card_drag || card_drag && !drag_save)
	{
		//Draw the background
		back->draw_pixbuf(get_style()->get_black_gc(),background.scaled,
						0,0,0,0,-1,-1, 
						Gdk::RGB_DITHER_NONE, 0, 0);		
	}
}

void Table::draw_deck()
{
	int i=0;
	for (card_it=card_list.begin();card_it!=card_list.end();card_it++)
	{
		//Draw the non-moving cards
		if(!(*card_it)->moving)
		{
			if(!(*card_it)->pointed)
			{
				//Draw the cards
				back->draw_pixbuf(get_style()->get_black_gc(),(*card_it)->card_f->scaled,
								0, 0,2+get_width()/40*i,get_height()-(*card_it)->card_f->scaled->get_height(),-1,-1, 
								Gdk::RGB_DITHER_NONE, 0, 0);
			}	
			else
			{			
				//Draw the cards
				back->draw_pixbuf(get_style()->get_black_gc(),(*card_it)->card_f->scaled,
								0, 0,2+get_width()/40*i,get_height()-(*card_it)->card_f->scaled->get_height()-get_height()/60,-1,-1, 
								Gdk::RGB_DITHER_NONE, 0, 0);
				
			}	
		}
		i++;
	}
	
}

//Return -1 if not and else the number of the card
int Table::on_deck()
{
	int x;int y;int nb_card=(card_list.size()-1);
	get_pointer(x,y);

	//On the deck ?	
	if( y >get_height()-card_list[0]->card_f->scaled->get_height() && y<get_height()-5 && x > 2 && x < 2 + nb_card*get_width()/40 +card_list[0]->card_f->scaled->get_width())
	{
		//Which card is it
		int c=(40*(x-2))/get_width();
		if (c>nb_card) c=nb_card;
		return c;
	}
	else return -1;
}

bool Table::on_button_press_event(GdkEventButton* event)
{
	if(event->button==1)
	{
		int c=on_deck();
		if(c!=-1)
		{
			std::cout<<"Drag begin"<<std::endl;
			
			//Evaluate the grab point on the card
			int x,y;
			get_pointer(x,y);					
			card_list[c]->drag_x=x-(2+get_width()/40*c);
			card_list[c]->drag_y=y-(get_height()-card_list[c]->card_f->scaled->get_height());
			card_list[c]->drag_xb=x;		
			card_list[c]->drag_yb=y;

			card_list[c]->moving=true; 
			
			//Copy of the dragged card
			card_dragged = new card;
			card_dragged = card_list[c];
			
			card_drag=true;
			
			//No card is being pointed
			for (card_it=card_list.begin();card_it!=card_list.end();card_it++)
			{			
				(*card_it)->pointed=false;
			}
		
		}
	}
	
	return true;	
}

bool Table::on_button_release_event(GdkEventButton* event)
{
	if(event->button==1)
	{
		//End of a drag, end all the process
		if(card_drag)
		{
			card_dragged->moving=false;					
			card_list.erase(std::find(card_list.begin(),card_list.end(),card_dragged)); //erase the card from the list
			drag_save=false;
			card_drag=false;
			draw_table();
			std::cout<<"La carte "<<card_dragged->value<<" a été jouée."<<std::endl;
		}
	}
	
	return true;	
}


void Table::deal_card(std::vector<int> list)
{
	card_list.empty();
	
	for (std::vector<int>::iterator it=list.begin();it!=list.end();it++)
	{		
		card* card_t = new card;
		card_t->card_f=(*card_file_list)[*it]; //pointer to the corresponding image file
		card_t->pointed=false;
		card_t->moving=false;
		card_t->drag_x=0;
		card_t->drag_y=0;
		card_t->drag_xb=0;
		card_t->drag_yb=0;
		card_t->value=*it;
		card_list.push_back(card_t);
	}	
}



