@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
  <!-- top tiles -->
  <div class="row" style="display: inline-block;">
    <div class="tile_count">
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <a href="{{ route('users.index') }}">
        <span class="count_top"
          ><i class="fa fa-users"></i> Total Users</span
        >
        <div class="count">{{ $users}}</div>
        <span class="count_bottom"
          ><i class="green"><i class="fa fa-sort-asc"></i>{{$curWkUsr}} </i> From this Week</span
        >
        </a>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <a href="{{ route('countries.index') }}">
        <span class="count_top"
          ><i class="fa fa-flag-checkered"></i> Total Countries</span
        >
        <div class="count">{{ $countries }}</div>
        <span class="count_bottom"
          ><i class="green"><i class="fa fa-sort-asc"></i>{{$curWkCount}} </i> From
          this Week</span
        >
        </a>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <a href="{{ route('companies.index') }}">
        <span class="count_top"
          ><i class="fa fa-building-o"></i> Total Companies</span
        >
        <div class="count">{{ $companies }}</div>
        <span class="count_bottom"
          ><i class="green"><i class="fa fa-sort-asc"></i>{{$curWkComp}} </i> From
          this Week</span
        >
        </a>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <a href="{{ route('health-centers.index') }}">
        <span class="count_top"
          ><i class="fa fa-hospital-o"></i> Health Centers</span
        >
        <div class="count">{{ $healthCenters }}</div>
        <span class="count_bottom"
          ><i class="green"><i class="fa fa-sort-asc"></i>{{$curWkHC}} </i> From
          this Week</span
        >
        </a>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <a href="{{ route('checkins.index') }}">
        <span class="count_top"
          ><i class="fa fa-checkin"></i> Total Checkins</span
        >
        <div class="count">{{ $checkins }}</div>
        <span class="count_bottom"
          ><i class="green"><i class="fa fa-sort-asc"></i>{{$curWkcheckin}} </i> From
          this Week</span
        >
        </a>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <a href="{{ route('notifications.index') }}">
        <span class="count_top"
          ><i class="fa fa-bell-o"></i> Total Notifications</span
        >
        <div class="count">{{ $notifications }}</div>
        <span class="count_bottom"
          ><i class="green"><i class="fa fa-sort-asc"></i>{{$curWkNotify}} </i> From
          this Week</span
        >
        </a>
      </div>
    </div>
  </div>
  <!-- /top tiles -->

  <div class="row">
    <div class="col-md-4 col-sm-4 ">
      <div class="x_panel Section">
        <div class="x_title text-right">
          <h2>Recent News</h2>
          <div class="clearfix"><a href="{{ route('news.index') }}"><strong>View All</strong></a></div>
        </div>
        <div class="x_content">
          <div class="dashboard-widget-content">
            <ul class="list-unstyled timeline widget">
              @forelse($recentNews as $news)
                <li>
                  <div class="block">
                    <div class="block_content">
                      <h2 class="title">
                        <a href="{{route('news.show',$news->id)}}">{{ $news->title }}</a>
                      </h2>
                      <div class="byline">
                        <span>{{$news->created_at->diffForHumans()}}</span> for <a>{{ ($news->for_employee)?'Massy Employee':'General User' }}</a>
                      </div>
                      <p class="excerpt">
                        {{ $news->snapshot }}
                      </p>
                    </div>
                  </div>
                </li>
              @empty
                <p>No Recent News Found</p>
              @endforelse
            </ul>
          </div>
        </div>
      </div>
    </div>

<!--     <div class="col-md-8 col-sm-8 ">
      <div class="row">
        <div class="col-md-12 col-sm-12 ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Visitors location <small>geo-presentation</small></h2>

              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="dashboard-widget-content">
                <div class="col-md-4">
                  <h2 class="line_30">
                    125.7k Views from 60 countries
                  </h2>

                  <table class="countries_list">
                    <tbody>
                      <tr>
                        <td>United States</td>
                        <td class="fs15 fw700 text-right">33%</td>
                      </tr>
                      <tr>
                        <td>France</td>
                        <td class="fs15 fw700 text-right">27%</td>
                      </tr>
                      <tr>
                        <td>Germany</td>
                        <td class="fs15 fw700 text-right">16%</td>
                      </tr>
                      <tr>
                        <td>Spain</td>
                        <td class="fs15 fw700 text-right">11%</td>
                      </tr>
                      <tr>
                        <td>Britain</td>
                        <td class="fs15 fw700 text-right">10%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div
                  id="world-map-gdp"
                  class="col-md-8 col-sm-12 "
                  style="height:230px;"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</div>
<!-- /page content -->
            @endsection