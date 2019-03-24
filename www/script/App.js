import React, { Component } from 'react';
import Transactions from './Components/Transactions/Transactions';

class App extends Component {
    render() {

        return (
            <section>
                <h1>React Section</h1>

                <Transactions/>
            </section>
        );

    }
}

export default App;