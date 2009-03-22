#include "chat.h"
#include <iostream>
#include <string>

Chat::Chat()
{
	set_editable(false);
	set_border_width(5);
}

Chat::~Chat()
{
}

void Chat::add_text(std::string text)
{
	Glib::RefPtr<Gtk::TextBuffer> text_buffer = get_buffer();
	Gtk::TextIter start_iter = text_buffer->end();
	text_buffer->insert(start_iter, text);
}