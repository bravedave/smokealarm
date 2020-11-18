/**
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * */

(_ => {
  if ('undefined' == typeof _.search)
    _.search = {};

  if ('undefined' == typeof _.search.address) {
    _.search.address = (request, response) => {
      _.post({
        url: '{{route}}',
        data: {
          action: 'search-properties',
          term: request.term

        },

      }).then(d => response('ack' == d.response ? d.data : []));

    };

  }

  if ('undefined' == typeof _.search.alarmMake) {
    _.search.alarmMake = (request, response) => {
      _.post({
        url: '{{route}}',
        data: {
          action: 'search-makes',
          term: request.term

        },

      }).then(d => response('ack' == d.response ? d.data : []));

    };

  }

  if ('undefined' == typeof _.search.alarmCompany) {
    _.search.alarmCompany = (request, response) => {
      _.post({
        url: '{{route}}',
        data: {
          action: 'search-suppliers',
          term: request.term

        },

      }).then(d => response('ack' == d.response ? d.data : []));

    };

  }

})(_brayworth_);
