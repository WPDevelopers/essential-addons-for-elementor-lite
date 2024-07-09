function Search404() {
    const reactPath = localize.eael_dashboard.reactPath,
        i18n = localize.eael_dashboard.i18n;

    return (
        <>
            <div className="ea__contents">
                <div className="ea__not-found-wrapper">
                    <img src={reactPath + 'images/not-found.png'} alt="img"/>
                    <h5>{i18n.search_not_found}</h5>
                </div>
            </div>
        </>
    );
}

export default Search404;