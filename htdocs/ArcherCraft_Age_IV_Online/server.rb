require 'sinatra'
require 'rest-client'
require 'json'

CLIENT_ID = ENV['f441a37a97925ea77c32']
CLIENT_SECRET = ENV['c14949f6e7e52e7aafba3a4fe2e8ab2a9360401c']

get '/' do
  erb :index, :locals => {:client_id => f441a37a97925ea77c32}
end