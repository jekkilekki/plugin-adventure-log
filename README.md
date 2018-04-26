# Adventure Log

## WordPress Plugin

### Description

After using [https://750words.com](750words.com) for a few days, I found I really enjoyed it, but I wanted to customize it a little more than that site allowed. So, I decided to create my own version that I could plugin to a WordPress site.

Additionally, I read [https://nerdfitness.com](Steve Kamb's) [https://levelupyourlife.com]("Level Up Your Life") and many of the things he wrote really resonated with me. I decided that instead of calling this plugin something _boring_ like "Journal", I'd make it a bit more _awesome_ by naming it "Adventure Log". A few other considerations I had were:

* Adventure Log
* Captain's Log
* Quest Log

Some of these may still be take the place of "Adventure Log" in the future (or be added to the main functionality),but for now, we're using "ALog" because it's that much better than "Blog" - one letter earlier in the alphabet.

### Features

The following are features of the plugin.

1. Front-end editing in WordPress
2. Creates a new User role and Custom Post Type to keep things separate from the main WP blog content
3. Ability to keep "Adventurers" (new User role) out of the back-end of WordPress and only allow front-end editing
4. Ability to add Tags to your Logs for easier searching
5. Ability to add a Featured Image to your Logs
6. Redirects Users to the Main Alog page after login

#### Admin Settings Page

1. Daily target word limit (default: 500)
2. Allow editing of Posts after the deadline (default: no)
3. Allow additional Posts on each date (default: no)
4. Allow creation of future Posts (default: no)
5. Allow public facing Posts (default: yes)
6. Override individual User settings

#### Front-end Settings Page

1. Change Adventurer (WP) profile information
2. Change (personal) target word limit
3. Change (personal) editing after the fact option
4. Most of the Admin options...

#### :new: Things to Track in Widgets or Post Meta

Inspiration from [Full Focus Planner](http://fullfocusplanner.com/)

- Top Ten (Year / Current) Goals List (editable, updates backend)
- Daily Big 3-7 (can add new things to track, different on a daily basis)
  - Each day pops up yesterday's list until complete as a reminder
  - Also can create "today's" list
- Routines (Morning, Night, can add new routines - should be accordion or dropdown to not take up too much space)
- Daily Quotations for Inspiration
- Weekly Review
- Quarterly Review
- Yearly Review

### Pages

The following is a list of the pages that are created and used by this plugin:

See this page for help: 
* [https://www.ibenic.com/include-or-override-wordpress-templates/](https://www.ibenic.com/include-or-override-wordpress-templates/)
* [https://gist.github.com/ashokmhrj/b5f6e28f15dc84601954](https://gist.github.com/ashokmhrj/b5f6e28f15dc84601954)

* `/alog/` - Adventure Log Home (opens a "new" editor and/or displays the archive for that month)
* `/alog/2018/` - Year archive - with a one-year calendar
* `/alog/2018/4/` - Month archive - with a one-month calendar
* `/alog/2018/4/5/` - Day archive - 
* `/alog/post-slug/` - Single Log page - typical WP content
* `/alog?page=settings/` - Settings page - Ajax form, posting to REST API
* `/alog/2018/4/5?page=new/` - New editor page - with today's date

#### Color Choosing Tools

* [https://uigradients.com/#Celestial](https://uigradients.com/#Celestial)
* [http://colrd.com/palette/](http://colrd.com/palette/)
* [https://www.materialui.co/colors](https://www.materialui.co/colors)
* [https://blog.graphiq.com/finding-the-right-color-palettes-for-data-visualizations-fcd4e707a283](https://blog.graphiq.com/finding-the-right-color-palettes-for-data-visualizations-fcd4e707a283)
* [https://adoriasoft.com/blog/mobile-app-design-14-trendy-color-schemes/](https://adoriasoft.com/blog/mobile-app-design-14-trendy-color-schemes/)
* [https://creativemarket.com/paul-dodd/1518840-Glorious-Gradients](https://creativemarket.com/paul-dodd/1518840-Glorious-Gradients)
* [http://eyetracking.upol.cz/color/](http://eyetracking.upol.cz/color/)
* [http://colorbrewer2.org/#type=diverging&scheme=RdGy&n=9](http://colorbrewer2.org/#type=diverging&scheme=RdGy&n=9)
* [https://seaborn.pydata.org/tutorial/color_palettes.html](https://seaborn.pydata.org/tutorial/color_palettes.html)
* [https://onlinehelp.tableau.com/current/pro/desktop/en-us/formatting_create_custom_colors.html](https://onlinehelp.tableau.com/current/pro/desktop/en-us/formatting_create_custom_colors.html)