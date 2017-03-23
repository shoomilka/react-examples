<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <title>Ajax Example</title>
      
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.0/react.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.0/react-dom.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />

        <script type="text/babel">
        $.ajaxSetup({
	    	headers: {
	    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    	}
	    });

        const divStyle = {
            height: '100vh',
            width: '100vw',
        };

        const centStyle = {
            position: 'fixed',
            top: '50%',
            left: '50%',
        };

        var meth = 'POST';
        var urlp = '/';

        var Ans = React.createClass({
            submit: function(co) {
                $.ajax({
                    type: meth,
                    url: urlp,
                    data: '_token = <?php echo csrf_token() ?>',
                    answer: co
                })
                .done(function(data) {
                    rerend(data);
                })
                .fail(function(jqXhr) {
                    console.log('failed to POST');
                });
            },

            render: function() {
                return (
                    <div className={"col-md-6 text-"+this.props.whe}>
                        <img src={this.props.image} onClick={() => this.submit(this.props.key)} />
                    </div>
                );
            }
        });
		
        function rerend(data){
            if(data == 'fin'){
                return ReactDOM.render(
                    <div className="container">
                        <div style={centStyle}>
                            <h1>FIN!</h1>
                            <a href="/result">Go to results</a>
                        </div>
                    </div>,
                    document.getElementById("content")
                );
            }
            ReactDOM.render(
                <AnsList answers={data['data']} question={data['que']} />,
                document.getElementById("content")
            );
        }

		var AnsList = React.createClass({
            start: function(e) {
                e.preventDefault()           
                $.ajax({
                    type: meth,
                    url: urlp,
                    data: '_token = <?php echo csrf_token() ?>'
                })
                .done(function(data) {
                    rerend(data);
                })
                .fail(function(jqXhr) {
                    console.log('failed to POST');
                });
            },

            render: function() {
                if(this.props.answers.length == 0) {
                    return <div className="container">
                                <div style={centStyle}>
                                    <button className="btn btn-default" onClick={this.start}>Start testing</button>
                                </div>
                           </div>
                }

                var rows = [];

                var answs = this.props.answers;
                var answer1;
                var answer2;
                var i = 100;
                
                rows.push(<div key={i-1}>{this.props.question}</div>);

                while((answer1=answs.pop()) != null){
                    if((answer2=answs.pop()) != null){
                        rows.push(<div key={i} className="row">
                                    <Ans key={answer1.id} image={answer1.sr} whe="right" />
                                    <Ans key={answer2.id} image={answer2.sr} whe="left" />
                                </div>);
                    }else{
                        rows.push(<div key={i} className="row">
                                    <Ans key={answer1.id} image={answer1.sr} />
                                </div>);
                    }
                    i++;
                }
                /*this.props.answers.forEach((answer) => {
                    rows.push(<Ans key={answer.id} image={answer.sr} />)
                });*/
                return (
                    <div style={divStyle}>
                        <div className="container">
                            {rows}
                        </div>
                    </div>
                );
            }
        });

		ReactDOM.render(
            <AnsList answers={[]} />,
            document.getElementById("content")
        );
        </script>
    </head>
   
    <body>
        <div id='content'></div>
    </body>

</html>