import React, { Component } from 'react';
import ReactSvg from 'react-svg';
import { Link } from 'react-router-dom';

class NavigationItem extends Component {

    constructor(){
        super();
        this.state = {
            activeNavigation: []
        }
    }

    componentWillMount(){
        if(this.props.active){
            this.setState({
                activeNavigation: this.props.active
            })
        }
    }

    render(){

        return(
          <li className={(this.state.activeNavigation === this.props.item.title) ? "active" : ""}>
              <Link to={this.props.item.url}><ReactSvg evalScripts="always" src={this.props.item.svg} svgClassName={this.props.item.title}/></Link>
          </li>
        );
    }
}

export default NavigationItem;