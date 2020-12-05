var AccidentOb = function () {
	var self = this;
	self.cave = null;
	self.date = null;
	self.incidentType = null;
	self.location = null;
	self.results = null;
	self.pdf = null;
};

var AccidentViewModel = function (accidents) {
	var self = this;
	self.accidents = accidents.sort(function (a, b) {
			return new Date(a.IncidentDate) - new Date(b.IncidentDate);
		});

	self.cavingAccidents = [];
	self.divingAccidents = [];
	self.otherAccidents = [];
	self.years = [];

	self.apply_filter = function (filter) {
		self.cavingAccidents = [];
		self.divingAccidents = [];
		self.otherAccidents = [];

		for (var i = 0; i < self.accidents.length; i++) {
			var a = self.accidents[i];
			if (filter(a)) {
				var ao = new AccidentOb;
				ao.cave = a.Cave;
				ao.incidentDate = self.get_date_str(a);				
				ao.incidentType = a.IncidentType;
				ao.location = a.Location;
				ao.results = a.Results;
				ao.complete = a.Complete;
				if ( a.Pdf != null )
					ao.pdf = 'https://caves.org/nss-business/publications/NSS_News/' + a.pdf;				

				switch (a.CavingType) {
				case 0: // caving accidents
					self.cavingAccidents.push(ao);
					break;
				case 1: // diving accidents
					self.divingAccidents.push(ao);
					break;
				case 2:
					self.otherAccidents.push(ao);
					break;
				}
			}
		}
	}

	self.getRowStyle = function(accident){
		if ( accident.complete === 1)
			return "";
		else
			return "color: red;";
	}
	
	self.months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

	self.get_date_str = function (accident) {
		var d = new Date(accident.IncidentDate)
			var dateStr;
		if (accident.DateAccuracy == 0){
				dateStr = self.months[d.getMonth()] + ' ' + d.getDate().toString();
				if ( self.show_year )
					dateStr = dateStr + ', ' + d.getFullYear().toString();				
		}
		else if (accident.DateAccuracy == 1){
			dateStr = self.months[d.getMonth()];
			if ( self.show_year)
				dateStr = self.months[d.getMonth()] + ' ' + d.getFullYear().toString();							
		}
		else if (accident.DateAccuracy == 2) {
			var m = d.getMonth();
			if (m < 3)
				dateStr = "Winter";
			else if (m < 6)
				dateStr = "Spring";
			else if (m < 9)
				dateStr = "Summer";
			else
				dateStr = "Fall";

			if ( self.show_year )
				dateStr = dateStr + ' ' + d.getFullYear().toString();
		}
		else if (accident.DateAccuracy == 3){
			dateStr = d.getFullYear().toString();
		}
		else if ( accident.DateAccuracy == 4){
			dateStr = "circa " + d.getFullYear().toString();
		}
		else{
			dateStr = "???";
		}
			
		return dateStr;
	}	
};
