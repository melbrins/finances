import React, { Component } from 'react';
import NavigationItem from './NavigationItem';
import './scss/navigation.scss';
import uuid from 'uuid';

import dashboardSvg from './svg/dashboard.svg';
import transactionSvg from './svg/transactions.svg';
import accountsSvg from './svg/accounts.svg';
import settingsSvg from './svg/settings.svg';

class Navigation extends Component {
    constructor(){
        super();
        this.state = {
            navItems: []
        }
    }

    componentWillMount() {
        this.setState({
            navItems:[
                {
                    id      : uuid.v4(),
                    title   : 'Dashboard',
                    url     : '/',
                    svg     : dashboardSvg
                },
                {
                    id      : uuid.v4(),
                    title   : 'Transactions',
                    url     : '/transactions',
                    svg     : transactionSvg
                },
                {
                    id      : uuid.v4(),
                    title   : 'Accounts',
                    url     : '/accounts',
                    svg     : accountsSvg
                },
                {
                    id      : uuid.v4(),
                    title   : 'Settings',
                    url     : '/settings',
                    svg     : settingsSvg
                }
            ]
        });
    }

    render(){
        let navItems;
        if(this.state.navItems){
            navItems = this.state.navItems.map(item => {
                // console.log(item);
                return(
                    <NavigationItem item={item} active={this.props.active} key={item.id}/>
                )
            });
        }
        return (
            <nav className="navigation">
                {navItems}
            </nav>
        );
    }
}

export default Navigation;