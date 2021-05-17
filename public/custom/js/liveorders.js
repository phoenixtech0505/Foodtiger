
"use strict";
var liveOrders=null;

function getLiveOrders(){
  axios.get('/liveapi').then(function (response) {
    liveOrders.neworders=response.data.neworders;
    liveOrders.accepted=response.data.accepted;
    liveOrders.done=response.data.done;
    liveOrders.calculateBiggest()
    console.log(response.data);
  })
  .catch(function (error) {
    console.log(error);
  });
};

window.onload = function () {

    var audio = new Audio('https://soundbible.com/mp3/old-fashioned-door-bell-daniel_simon.mp3');
    var welcomeAudio = new Audio('https://soundbible.com/mp3/Blop-Mark_DiAngelo-79054334.mp3');

    //VUE CART
    liveOrders = new Vue({
      el: '#liveorders',
      data: {
        neworders: [],
        accepted: [],
        done:[],
        biggestBefore:null,
      },
      methods:{
        calculateBiggest(){
          //audio.play();
          if(this.biggestBefore==null){
            //initial -- loop over the neworders to find the biggest it
            console.log("initial");
            this.biggestBefore=this.findBiggest();
            console.log(this.biggestBefore);
            welcomeAudio.play();
          }else{
            //on update
            //Find biggest
            console.log("update");
            var newBiggest=this.findBiggest();
            if(newBiggest>this.biggestBefore){
              //Set new biggest
              console.log("bigger order found");
              this.biggestBefore=newBiggest;
              console.log("play sound");
              audio.play();
            }else{
              console.log("Same, do nothing");
            }
          }
        },
        findBiggest(){
          var biggest=0;
          this.neworders.forEach(element => {
            if(element.id>biggest){
              biggest=element.id;
            }
          });
          return biggest;
        }
      }
    })

    getLiveOrders();
    setInterval(getLiveOrders, 10000);

}