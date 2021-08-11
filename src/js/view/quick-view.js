const QuickView = {
	test:(a,b) => {
		console.log(a)
		console.log(b)
	}
}

ea.hooks.addAction( 'quickViewAddMarkup', 'ea', QuickView.test)

