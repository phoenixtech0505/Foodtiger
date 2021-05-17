import React, { Component } from 'react';
import QR from './QR';

export default class PrintPage extends Component {
    constructor(props){
        super(props);
        this.state = {

        };
        console.log('data from component'+JSON.stringify(props));
    }


     renderRow(){
        var elements=[];
        for (let index = 0; index < this.props.numCol; index++) {
            elements.push(<QR color1={this.props.color1} color2={this.props.color2} type={this.props.type} className="img-thumbnail" value={this.props.value} />)  
       } 
       return elements;
     }
    renderRows(){
        var style={  display: "flex", flexDirection:"row", justifyContent: "space-around"};
        var elements=[];
        for (let index = 0; index < this.props.numRow; index++) {
            elements.push(<div className="col-md-12" style={style}>{this.renderRow()}</div>);
        } 
        return elements;
    }
    

    render() {
       
        return (
            
            <div className="row">
                {this.renderRows()}
            </div>
        )
    }
}