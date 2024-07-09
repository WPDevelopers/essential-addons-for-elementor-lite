import consumer from "../context";
import React from "react";

function Search404() {
    const {eaState} = consumer(),
        i18n = localize.eael_dashboard.i18n;

    return (
        <>
            <div className="ea__contents">
                <div className="ea__not-found-wrapper">
                    <img src='../../dist/images/not-found.png' alt="img"/>
                    <h5>No elements found with these keyword </h5>
                </div>
            </div>
        </>
    );
}

export default Search404;