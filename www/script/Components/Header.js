import React, { Component } from 'react';
import logo from '../logo.svg';

class Header extends Component{
    constructor(){
        super();
        this.state = {
            showHeader: true
        }
    }

    handleAlert(){
        this.setState(prevState => ({
            showHeader : !prevState.showHeader
        }));
    }

    render(){
        var header = this.state.showHeader ?
                <div>
                    <img src={logo} className="App-logo" alt="logo"/>
                    <h1>Finance Master</h1>
                    <p>
                        Welcome young padawan
                    </p>
                    <a href="#" onClick={this.handleAlert.bind(this)} className="App-link">
                        Start your adventure
                    </a>
                </div>

            :
                <div>
                    <a href="#" onClick={this.handleAlert.bind(this)} className="App-link">
                        Start your adventure
                    </a>
                </div>;

        return(
            <header className="App-header">
                {header}
            </header>


        );
    }
}

export default Header;