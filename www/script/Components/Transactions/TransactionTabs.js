import React, { Component } from 'react';
import {Link} from 'react-router-dom';
import {
    HashRouter,
    Route
} from 'react-router-dom';

import{
    transactionCategory,
    transactionMerchant,
    transactionTimeline
} from './TransactionTabsContent';

class TransactionTabs extends Component{
    render(){
        return(
            <Tabs
                activeKey={this.state.key}
                onSelect={this.handleSelect}
                id="controlled-tab-example"
            >
                <Tab eventKey={1} title="Tab 1">
                    Tab 1 content
                </Tab>
                <Tab eventKey={2} title="Tab 2">
                    Tab 2 content
                </Tab>
                <Tab eventKey={3} title="Tab 3" disabled>
                    Tab 3 content
                </Tab>
            </Tabs>
        );
    }
}

export default TransactionTabs