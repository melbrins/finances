import React from 'react';
import TransactionTabs from "./TransactionTabs";
import Navigation from "../Navigation/Navigation";

export const transactionCategory = () =>
    <section className="transactionCategory">
        <TransactionTabs/>
        <h1>Category</h1>
        <Navigation active="Transactions"/>
    </section>

export const transactionMerchant = () =>
    <section className="transactionMerchant">
        <TransactionTabs/>
        <h1>Merchant</h1>
        <Navigation active="Transactions"/>
    </section>

export const transactionTimeline = () =>
    <section className="transactionTimeline">
        <TransactionTabs/>
        <h1>Timeline</h1>
        <Navigation active="Transactions"/>
    </section>