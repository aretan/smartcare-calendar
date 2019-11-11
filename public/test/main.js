var currentYear = new Date().getFullYear();

console.log(Calendar);

new Vue({
  el: '#app',
  components: { Calendar },
  data: function() {
    return {
      show: false,
      currentId: null,
      currentStartDate: null,
      currentEndDate: null,
      currentName: null,
      currentLocation: null,
      dataSource: [
        {
          id: 0,
          name: 'Google I/O',
          location: 'San Francisco, CA',
          startDate: new Date(currentYear, 4, 28),
          endDate: new Date(currentYear, 4, 29)
        },
        {
          id: 1,
          name: 'Microsoft Convergence',
          location: 'New Orleans, LA',
          startDate: new Date(currentYear, 2, 16),
          endDate: new Date(currentYear, 2, 19)
        },
        {
          id: 2,
          name: 'Microsoft Build Developer Conference',
          location: 'San Francisco, CA',
          startDate: new Date(currentYear, 3, 29),
          endDate: new Date(currentYear, 4, 1)
        },
        {
          id: 3,
          name: 'Apple Special Event',
          location: 'San Francisco, CA',
          startDate: new Date(currentYear, 8, 1),
          endDate: new Date(currentYear, 8, 1)
        },
        {
          id: 4,
          name: 'Apple Keynote',
          location: 'San Francisco, CA',
          startDate: new Date(currentYear, 8, 9),
          endDate: new Date(currentYear, 8, 9)
        },
        {
          id: 5,
          name: 'Chrome Developer Summit',
          location: 'Mountain View, CA',
          startDate: new Date(currentYear, 10, 17),
          endDate: new Date(currentYear, 10, 18)
        },
        {
          id: 6,
          name: 'F8 2015',
          location: 'San Francisco, CA',
          startDate: new Date(currentYear, 2, 25),
          endDate: new Date(currentYear, 2, 26)
        },
        {
          id: 7,
          name: 'Yahoo Mobile Developer Conference',
          location: 'New York',
          startDate: new Date(currentYear, 7, 25),
          endDate: new Date(currentYear, 7, 26)
        },
        {
          id: 8,
          name: 'Android Developer Conference',
          location: 'Santa Clara, CA',
          startDate: new Date(currentYear, 11, 1),
          endDate: new Date(currentYear, 11, 4)
        },
        {
          id: 9,
          name: 'LA Tech Summit',
          location: 'Los Angeles, CA',
          startDate: new Date(currentYear, 10, 17),
          endDate: new Date(currentYear, 10, 17)
        }
      ],
      contextMenuItems: [
        {
          text: "Update",
          click: evt => {
            this.currentId = evt.id;
            this.currentStartDate = evt.startDate.toISOString().substring(0, 10);
            this.currentEndDate = evt.endDate.toISOString().substring(0, 10);
            this.currentName = evt.name;
            this.currentLocation = evt.location;
            this.show = true;
          }
        },
        {
          text: "Delete",
          click: evt => {
            this.dataSource = this.dataSource.filter(item => item.id != evt.id);
          }
        }
      ]
    };
  },
  methods: {
    selectRange: function(e) {
      this.currentId = null;
      this.currentName = null;
      this.currentLocation = null;
      this.currentStartDate = e.startDate.toISOString().substring(0, 10);
      this.currentEndDate = e.endDate.toISOString().substring(0, 10);
      this.show = true;
    },
    saveEvent: function(e) {
      if (this.currentId == null) {
        // Add event
        var id = Math.max(...this.state.dataSource.map(evt => evt.id)) + 1;
        
        this.dataSource.push({
          id: id,
          startDate: this.currentStartDate,
          endDate: this.currentEndDate,
          name: this.currentName,
          location: this.currentLocation,
        });
      }
      else {
        // Update event
        var index = this.dataSource.findIndex(c => c.id == this.currentId);
        this.dataSource[i].startDate = this.currentStartDate;
        this.dataSource[i].endDate = this.currentEndDate;
        this.dataSource[i].name = this.currentName;
        this.dataSource[i].location = this.currentLocation;
      }
    }
  }
});