import React from 'react';

function CommunityBox(props) {
    return (
        <>
            <div className="ea__connect-others">
                <div className="ea__others-icon eaicon-1">
                    <i className="eaicon ea-github"></i>
                </div>
                <h5>GitHub & Support</h5>
                <p className="mb-6">Encountering a problem? Seek assistance through live chat or by
                    submitting.
                </p>
                <a href="#">
                    <button>
                        <span className="underline">Create Ticket</span>
                        <i className="eaicon ea-right-arrow"></i>
                    </button>
                </a>
            </div>
            <div className="ea__connect-others">
                <div className="ea__others-icon eaicon-2">
                    <i className="eaicon ea-community"></i>
                </div>
                <h5>Join Community</h5>
                <p className="mb-6">Encountering a problem? Seek assistance through live chat or by
                    submitting.
                </p>
                <a href="#">
                    <button>
                        <span className="underline">Join with us</span>
                        <i className="eaicon ea-right-arrow"></i>
                    </button>
                </a>
            </div>
        </>
    );
}

export default CommunityBox;