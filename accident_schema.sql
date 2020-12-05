CREATE TABLE acaAccidents (
	accidentId int not null auto_increment,
	date datetime not null,
	dateIsEstimate int, --0 = exact, 1 = to the month, 2 = season, 3 = year
	cavename nvarchar(128),
	location nvarchar(128),
	result nvarchar(128),
	incidentType nvarchar(128),
	issueYear int,
	cavingType int --0 = caving, 1 = cave diving, 2 = cave related
)