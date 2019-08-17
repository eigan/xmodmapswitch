# xmodmapswitch
Keep your xmodmaps in `~/.Xmodmaps/` and this utility will find
the next map file and replace your `~/.Xmodmap`.

### How it works
1) Looks for files in `~/.Xmodmaps/`
2) Finds the next .Xmodmap file 
3) Creates a diff between current and new
4) Executes `xmodmap`
5) Copies the content for the next Xmodmap file into `~/.Xmodmap`

#### Why create a diff
Using `xmodmap` on only changed keycodes is _a lot_ faster.


## Todo
- Better error handling
- Replace PHP code with Rust


### Setup
```sh
mkdir ~/.Xmodmaps
cp ~/.Xmodmap ~/.Xmodmaps/
git clone https://github.com/eigan/xmodmapswitch.git
```
#### i3
```
bindcode <keycode> exec php <path/to/project>/xmodmapswitch.php
```
