import React, { Component } from 'react'
import {QRNormal,QRRandRect,QRDsj,QR25D,QRImage,QRResImage,QRBubble,QRLine} from 'react-qrbtf'

export default class QR extends Component {
    constructor(props){
        super(props);
    }

    render() {

        switch (this.props.type) {
            case "QRNormal":
                return (<QRNormal posColor={this.props.color1} otherColor={this.props.color2} className={this.props.className} value={this.props.link} />)
            case "QRRandRect":
                return (<QRRandRect className={this.props.className} value={this.props.link} />)
            case "QRDsj":
                return (<QRDsj className={this.props.className} value={this.props.link} />)
            case "QRRandRect":
                return (<QRRandRect className={this.props.className} value={this.props.link} />)
            case "QR25D":
                return (<QR25D  topColor={this.props.color1} leftColor={this.props.color2} rightColor={this.props.color2} className={this.props.className} value={this.props.link} />)
            case "QRImage":
                return (<QRImage posColor={this.props.color1} darkColor={this.props.color2} lightColor={"#FFFFFF"} className={this.props.className} value={this.props.link} />)
            case "QRResImage":
                return (<QRResImage posColor={this.props.color1} otherColor={this.props.color2}  className={this.props.className} value={this.props.link} />)
            case "QRBubble":
                return (<QRBubble posColor={this.props.color1} circleColor={this.props.color2}  className={this.props.className} value={this.props.link} />)
            case "QRLine":
                return (<QRLine posColor={this.props.color1} lineColor={this.props.color2} className={this.props.className} value={this.props.link} />)
                
        
            default:
                return (<QRNormal value={this.props.link} />)
        }
    }
}
