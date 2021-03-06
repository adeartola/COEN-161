 		//var dataPerWeek = [ 50 ,45 , 30 ,60, 50, 2, 5 ];
 		var dataPerWeek = new Array();
 		var numWeeks;
        var maxY = 60;
        var stepSize = 10;
    	var topPadding = 50;
        var rightPadding = 70;
        var margin = 13;
        var yName = "Students";
        var xName = "Session";
        
 		//For window resize
        var smallestWidth = 525;
 		
 		function initHistogram(){
			$.post("fetchEnrollment.php") 
			.done(function(data) {
				var parsedData = JSON.parse(data);
				dataPerWeek.push.apply(dataPerWeek, parsedData);
				numWeeks = dataPerWeek.length;
				displayData();
			})		
			.fail(function() { alert("AJAX FAILED"); });       
		}
		
		// resizes canvas on window resize
        window.onresize = function(event) {
        		displayData()
        }
        
        function displayData() {
        	
        	var histogram = document.getElementById("histogram");
        	var ctx = histogram.getContext("2d");
  			ctx.canvas.height = 450;
			var disWidth;
			if ((window.innerWidth*.78) > 1084)
				disWidth= window.innerWidth * .78;
			else
				disWidth= 1084;
  			ctx.canvas.width = disWidth;
  		
            // Display axis names
            ctx.fillStyle = "black";
            ctx.font = "18pt Ariel";
            ctx.fillText(yName, 10, topPadding - margin - 20);
           	ctx.fillText(xName, histogram.width - rightPadding - 10 , histogram.height - 5);
            
            //Compensate for variable size
            var yMultiplier = (histogram.height - topPadding - margin) / (maxY);
            var xMultiplier = (histogram.width - rightPadding) / (numWeeks + 1);
            
            //Background Line
            ctx.strokeStyle = "gray";
            //Display Gridlines and Row Numbers
            ctx.font = "12pt Ariel"
            ctx.beginPath();
            for (var position = maxY, i = 0; position >= 0; position -= stepSize, i++) {
                var loc = topPadding + (yMultiplier * i * stepSize);
                ctx.fillText(position, margin, loc - margin);
                ctx.moveTo(rightPadding - 25, loc - margin)
                ctx.lineTo(histogram.width, loc - margin)
            }
            ctx.stroke();
            ctx.closePath();
            
            // Label Data
            ctx.font = "14pt Ariel";
            ctx.textBaseline = "bottom";
            for (var i = 0; i < numWeeks; i++) {
                var y = histogram.height - dataPerWeek[i] * yMultiplier - 10;
                ctx.fillText(dataPerWeek[i], xMultiplier * (i + 1) + 15, y - margin*2);
                ctx.fillText((i+1), xMultiplier * (i + 1) + 15, histogram.height);
            }
			var colors= new Array("red", "orange", "yellow", "green", "blue", "indigo", "violet")
            // Draw Data Bars
            ctx.translate(0, histogram.height - margin);
            for (var i = 0; i < numWeeks; i++) {
            	ctx.fillStyle = colors[i];
                ctx.fillRect(xMultiplier*(i + 1)+10, -margin, xMultiplier/3, -(dataPerWeek[i]*yMultiplier));
                ctx.fillStyle = 'gray';
                ctx.fillRect(xMultiplier*(i + 1)+(xMultiplier/3)+10, -margin, xMultiplier/10, -(dataPerWeek[i]+1)*yMultiplier);
                parallelogram(ctx, (i+1)*xMultiplier+10, -dataPerWeek[i]*yMultiplier -margin, xMultiplier/3, -yMultiplier , xMultiplier/10) ;
			}
        }
 		
 		// X & Y are top left coordinates
 		function parallelogram( ctx, x, y, width, height , offset) {
 			ctx.shadowColor = 'rgba(0,0,0, 0.0)';
    		ctx.fillStyle = '#000000';
      		ctx.strokeStyle = '#000000';
    		ctx.beginPath();
    		ctx.moveTo(x , y);
    		ctx.lineTo(x + offset, y + height);
    		ctx.lineTo(x + offset + width, y + height);
    		ctx.lineTo(x + width, y);
    		ctx.lineTo(x , y);
    		ctx.closePath();  
    		ctx.fill();
    	}