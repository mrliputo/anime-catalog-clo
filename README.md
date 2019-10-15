![Alt text](https://raw.githubusercontent.com/normansyarif/anime-catalog/master/assets/images/screenshots/1.png "Homepage")
<br /><br />
![Alt text](https://github.com/normansyarif/anime-catalog/raw/master/assets/images/screenshots/2.png "Episode list")
<br /><br />

### How to use:
Put your anime folders into **'anime-catalog/Anime'** folder. Make sure to put all the episodes of one anime title in their root folder. Do not put them in subfolder, otherwise they won't show up on Episode list page.

<pre>
anime-catalog/
├── Anime
│   ├── Title 1 
│   │   ├── Episode 1.mp4 
│   │   └── Episode 2.mp4
│   ├── Title 2
│   │   ├── Episode 1.mp4
│   │   └── Episode 2.mp4
└── └── Title 3/
├── anime.php
├── anime-local.php
└── lastwatched
</pre>

### Note:
By default, when you click on the episode, it will retrieve the video file from the server and stream it to your browser. If you want to watch it locally, you need to:
* open **anime.php** then edit the value of **$baseDir** variable to the absolute path of where you put your anime collection
* then, you can play the video by dragging the episode to the VLC icon in your Dock (MacOS).
