Game.Assetloader = function(game){

  this.preloadbar = null;
};

Game.Assetloader.prototype = {
  preload:function(){
    this.preloadBar = this.add.sprite(this.world.centerX,this.world.centerY,'preloaderBar');
    this.preloadBar.anchor.setTo(0.5,0.5);

    this.time.advancedTiming = true;
    this.load.setPreloadSprite(this.preloadBar);


  },


  create:function(){

    this.state.start('landinglev');
  }
}
