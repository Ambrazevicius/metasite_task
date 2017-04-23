var Table = Reactable.Table,
    Tr = Reactable.Tr,
    Td = Reactable.Td;

var list_url = 'http://localhost/metasite_task/web/list.json';
var delete_url = 'http://localhost/metasite_task/web/listing/delete';



var formatTime = function(unixTimestamp) {

    var dt = new Date(unixTimestamp * 1000);

    var hours = dt.getHours();
    var minutes = dt.getMinutes();
    var seconds = dt.getSeconds();

    if (hours < 10)
        hours = '0' + hours;

    if (minutes < 10)
        minutes = '0' + minutes;

    if (seconds < 10)
        seconds = '0' + seconds;

    return dt.toDateString() + " " +hours + ":" + minutes + ":" + seconds;
}

var TimePlease = React.createClass({
    render:function(){
        var cts = this.props.data;
        var formattedTime = formatTime(cts);
        return (
            <div>
        { formattedTime }
            </div>
        );
    }
})


var Listing = React.createClass({
    getInitialState: function () {
        return {
            jobs: []
        }
    },
    componentDidMount: function () {
        var _this = this;
        this.serverRequest =
            axios
                .get(list_url)
                .then(function (result) {
                    _this.setState({
                        jobs: result.data
                    });
                })
    },
    componentWillUnmount: function () {
        this.serverRequest.abort();
    },
    render: function () {

        console.log(this.state.jobs);

        return (

        <Table className="table" id="table" sortable={true}>
        {this.state.jobs.map(function (job, index) {

            return <Tr key={ index } id={job.id} ><Td column="name">{job.name}</Td><Td column="email">{job.email}</Td><Td column="Submit date">{job.createdAt}</Td><Td column="name">{job.name}</Td><Td column="action"><Buttons id={job.id} /></Td></Tr>

        })}

        </Table>

        );
    }
});

var Url = React.createClass({
    render: function(){

        var id = 'view/'+this.props.id;

        return(

            <a href={id} className="btn btn-success btn-sm" >{this.props.title}</a>

        );
    }
})

var Buttons = React.createClass({

        delAction: function(){

            var id = this.props.id;
            $.ajax({
                type: "POST",
                url: delete_url,
                data: id,
                success: function(response) {
                    console.log(response);
                    $( "#"+response.id ).hide();
                }
            });

        },
        render: function(){
            return(
            <div>
            <Url id={this.props.id} title="View" />&nbsp;
            <button type="button" className="btn btn-danger btn-sm" onClick={this.delAction}>Delete</button>
            </div>
            );
        }
});



ReactDOM.render(
    <Listing />,
    document.getElementById('listing')
);