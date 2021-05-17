import React, { Component} from 'react';
import ReactDOM from 'react-dom';
import QR from './QR';
import { TwitterPicker } from 'react-color';

const svgHead = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n " +
    "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 20010904//EN\" \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">\n";
  

export default class QRBuilder extends Component {
    constructor(props){
        super(props);
        this.state = {
            type:"QRNormal",
            imgData:"",
            numRow:5,
            numCol:3,
            url:JSON.parse(props.data).url,
            passedData:JSON.parse(props.data),
            color1:'#000000',
            color2:'#000000'
        };
       // console.log('data from component'+JSON.stringify(JSON.parse(props.data).url));
        this.handleChangeColor1=this.handleChangeColor1.bind(this);
        this.handleChangeColor2=this.handleChangeColor2.bind(this);
        this.handleChangeColoumns=this.handleChangeColoumns.bind(this);
        this.downloadJPEG=this.downloadJPEG.bind(this);
        this.setImgData=this.setImgData.bind(this);
        this.downloadSVG=this.downloadSVG.bind(this);
    }

    handleChangeColoumns(event) {
        this.setState({numCol: event.target.value});
      }
    
    handleChangeColor1(color) {
        this.setState({ color1: color.hex });
    };

    handleChangeColor2(color){
        this.setState({ color2: color.hex });
    };
    
    downloadJPEG(){
        var _this=this;
        return new Promise(resolve => {
            console.log("saveImg started");
            _this.saveImg(_this.state.type,  document.getElementById("theQR").innerHTML, 1500, 1500).then((res) => {
                console.log("Promise received ")
                resolve(res);
            });
        });
    }

    downloadSVG(){
        var _this=this;
        return new Promise(resolve => {
            console.log("saveImg started");
            _this.saveImgSVG(_this.state.type,  document.getElementById("theQR").innerHTML, 1500, 1500).then((res) => {
                console.log("Promise received ")
                resolve(res);
            });
        });
    }

    saveImgSVG(value, content, width, height) {
        let htmlContent = [svgHead + content]
        let bl = new Blob(htmlContent, {type: "image/svg+xml"})
        let a = document.createElement("a")
        let filename = "QRcode_" + value + ".svg"

        a.href = URL.createObjectURL(bl)
        a.download = filename
        a.hidden = true
        a.click()
    }

    

    saveImg(value, content, width, height) {
        // Finish creating downloadable data
        let filename = "QRcode_" + value + ".jpg";
        const wrap = document.createElement('div');
        wrap.innerHTML = content;

       
    
        const $svg = wrap.firstChild
        const $clone = $svg.cloneNode(true);
    
        $clone.setAttribute('width', width);
        $clone.setAttribute('height', height);
    
        const svgData = new XMLSerializer().serializeToString($clone);
    
        let canvas = document.createElement('canvas');
    
        // Image will be scaled to the requested size.
        // var size = data.requestedSize;
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
    
        let ctx = canvas.getContext('2d');
        let img = document.createElement('img');
        console.log(btoa(svgData));
        img.setAttribute('src', 'data:image/svg+xml;base64,' + btoa(svgData));
    
        console.log("Before");
        return new Promise(resolve => {
            console.log("In promize");
            if (!img.complete) {
                // add onload listener here
                console.log("Not completed - add event listener");
                img.onload = () => {
                    console.log("resolved");
                    ctx.fillStyle = 'white'
                    ctx.fillRect(0, 0, width, height)
                    ctx.drawImage(img, 0, 0, width, height);
                    // `download` attr is not well supported
                    // Will result in a download popup for chrome and the
                    // image opening in a new tab for others.
        
                    let a = document.createElement('a');
                    let data = canvas.toDataURL('image/jpeg', 0.8);
                    a.setAttribute('href', data)
                    a.setAttribute('target', 'download')
                    a.setAttribute('download', filename);
                    a.click();
        
                    resolve(data)
                };
            }else{
                console.log("Image completed");
            }
            
        })
    }

    setImgData(res){
        this.setState({
            imgData:res
        })
    }

    render() {
        var imageStyle= {width:"130px",height:"130px"};
        var QRStylesPart1=["QRNormal","QRRandRect","QRDsj","QR25D"];
        var QRStylesPart2=["QRImage","QRResImage","QRBubble","QRLine"];
        return (
            <div className="row">
                <div className="col-xl-4">
                    <br/>
                    <div className="card bg-secondary shadow">
                        <div className="card-header bg-white border-0">
                            <div className="row align-items-center">
                                <div className="col-8">
                                    <h3 className="mb-0">{this.state.passedData.titleGenerator}</h3>
                                </div>
                            </div>
                        </div>

                        <div className="card-body">
                            <h6 className="heading-small text-muted mb-4">{this.state.passedData.selectQRStyle}</h6>

                            <div className="row">
                                <div className="col-md-12" style={{ height: "160px", display: "flex", flexDirection:"row", justifyContent: "space-around"}}>
                                    {QRStylesPart1.map((value, index) => {
                                            return <div key={value}  onClick={()=>{this.setState({type:value})}} style={imageStyle}><QR type={value} className="img-thumbnail" value={this.state.url} /></div>
                                    })}
                                    
                                    
                                </div>
                                <div className="col-md-12" style={{ height: "160px", display: "flex", flexDirection:"row", justifyContent: "space-around"}}>
                                    {QRStylesPart2.map((value, index) => {
                                            return <div key={value} onClick={()=>{this.setState({type:value})}} style={imageStyle}><QR type={value} className="img-thumbnail" value={this.state.url} /></div>
                                    })}
                                </div>
                            </div>

                            <h6 className="heading-small text-muted mb-4">{this.state.passedData.selectQRColor}</h6>
                            <br />
                            <span>{this.state.passedData.color1}</span><br />
                            <TwitterPicker
                                    color={ this.state.color1 }
                                    onChangeComplete={ this.handleChangeColor1 }
                                />
                            
                            <br /><br />
                            <span>{this.state.passedData.color2}</span><br />
                            <TwitterPicker
                                    color={ this.state.color2 }
                                    onChangeComplete={ this.handleChangeColor2 }
                                />           
                        </div>

                    </div>  
                </div>
           
                <div className="col-xl-4">
                    <br/>
                    <div className="card bg-secondary shadow">
                            <div className="card-header bg-white border-0">
                                <div className="row align-items-center">
                                    <div className="col-8">
                                        <h3 className="mb-0">{this.state.passedData.titleDownload}</h3>
                                    </div>
                                </div>
                            </div>

                            <div className="card-body" id="section-to-print" style={{height:"500px"}}>
                                <div id="theQR">
                                    <QR link={this.state.url} color1={this.state.color1} color2={this.state.color2} type={this.state.type} className="img-thumbnail" value={this.state.url} />
                                </div>
                            <br />
                            <button  onClick={()=>{this.downloadJPEG().then(res => this.setImgData(res))}} type="button" className="btn btn-success">{this.state.passedData.downloadJPG}</button>
                            <br /><br /><br /><br />
                            </div>

                            <div  style={{display:"none"}}>
                                {
                                    this.state.imgData.length > 0 ? <div id="dl-image"><div id="dl-image-inner"><img id="dl-image-inner-jpg" src={this.state.imgData} alt="长按保存二维码" /></div></div> : null
                                }
                            </div>

                    </div>


                </div>


                <div className="col-xl-4">
                    <br/>
                    <div className="card bg-secondary shadow">
                            <div className="card-header bg-white border-0">
                                <div className="row align-items-center">
                                    <div className="col-8">
                                        <h3 className="mb-0">{this.state.passedData.titleTemplate}</h3>
                                    </div>
                                </div>
                            </div>

                            <div className="card-body" id="section-to-print">
                            <div id="carouselExampleIndicators" className="carousel slide" data-ride="carousel">
                                <ol className="carousel-indicators">
                                {this.state.passedData.templates.map((value, index) => {
                                            return (<li data-target="#carouselExampleIndicators" data-slide-to={index} className={index==0?"active":""} />)
                                    })}
                                </ol>
                                <div className="carousel-inner">
                                    {this.state.passedData.templates.map((value, index) => {
                                            return (<div key={value} className={"carousel-item "+(index==0?"active":"")} >
                                            <img className="d-block w-100" src={value} />
                                        </div>)
                                    })}
                                   
                                </div>
                                <a className="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <span className="carousel-control-prev-icon" aria-hidden="true" />
                                    <span className="sr-only">Previous</span>
                                </a>
                                <a className="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <span className="carousel-control-next-icon" aria-hidden="true" />
                                    <span className="sr-only">Next</span>
                                </a>
                                </div>
                                <br />
                                <a  href={this.state.passedData.linkToTemplates} target="_blank" type="button" className="btn btn-success">{this.state.passedData.downloadPrintTemplates}</a> 
                            </div>
                           

                    </div>


                </div>






            </div>
        );
    }
}

if (document.getElementById('qrgen')) {
    var data = document.getElementById('qrgen').getAttribute('data');
    ReactDOM.render(<QRBuilder data={data} />, document.getElementById('qrgen'));
}
